<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Events\EndingSoonEvent;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\UploadImageTrait;

use App\Http\Requests\DateRangeRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RoomController extends Controller
{
    use UploadImageTrait;


    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        try {
            // Filter rooms based on room type name
            $rooms = Room::with('roomType')
                ->whereHas('roomType', function ($query) use ($request) {
                    if ($request->has('name')) {
                        $query->where('name', 'like', '%' . $request->name . '%');
                    }
                })
                ->orderBy('floorNumber', 'asc')
                ->paginate(20);

            return view('Admin.pages.dashboard.rooms.index', compact('rooms'));
        } catch (\Exception $e) {
            Log::error('Error in RoomController@index: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roomTypes = RoomType::all();
        return view('Admin.pages.dashboard.rooms.create', compact('roomTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {   
        $validatedData=$request->validated();
        //compute room cost per night
        $typeOfThisRoom=RoomType::find($request->room_type);
        $sumPricesOfAllAvailableServices=$typeOfThisRoom->services->sum('price');
        //
        if($request->has('images')){
            $roomImages=array();
            foreach($request->file('images') as $key=>$img){
                $path=$this->UploadMultipleImages($img,'rooms',$validatedData['code'].$key);
                if($path){
                    array_push($roomImages,$path);
                }
            }
        }
        $room=Room::create([
            "room_type_id" => $request->room_type,
            "code" => $validatedData['code'],
            "floorNumber" => $validatedData['floorNumber'],
            "description" => $validatedData['description'],
            "images" =>  json_encode($roomImages),
            "status" => $validatedData['status'],
            "price" => $typeOfThisRoom->price +$sumPricesOfAllAvailableServices,
        ]);
        return redirect()->route('rooms.index')->with('status','Room Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('Admin.pages.dashboard.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        return view('Admin.pages.dashboard.rooms.edit', compact('room','roomTypes'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room){
        try {
            $existingImages =json_decode($room->images,true);
            $validatedData=$request->validated();
            if ($request->filled('delete_images')) {
                $deleteImages = json_decode($request->delete_images, true);
                foreach ($deleteImages as $image) {
                    // Remove the image file from the server
                    $filePath = public_path('images/'.$image);
                    if (file_exists($filePath)) {
                        $this->deleteImage($image);
                    }
                    // Remove the image from the existing images array
                    $existingImages = array_filter($existingImages, function ($img) use ($image) {
                        return $img !== $image;
                    });
                }
            }
            // Handle addition of new images
            if ($request->hasfile('new_images')) {
                foreach ($request->file('new_images') as $key=>$image) {
                    $path=$this->UploadMultipleImages($image,'rooms',$validatedData['code'].$key);
                    $existingImages[] = $path;
                }
            }
            $room->code =$request->code ?? $room->code;
            $room->room_type_id =$request->room_type_id ?? $room->room_type_id;
            $room->floorNumber = $request->floorNumber ?? $room->floorNumber;
            $room->price = $request->price ?? $room->price;
            $room->status = $request->status ?? $room->status;
            $room->description = $request->description ?? $room->description;
            $room->images = json_encode($existingImages);// Encode the updated images array to JSON and save to the database
            $room->save();
            return redirect()->route('rooms.index')->with('status', 'Room updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error in RoomController@update: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room){
        try {
            $images=json_decode($room->images,true);
            foreach ($images as $image) {
                // Remove the image file from the server
                $filePath = public_path('images/'.$image);
                if (file_exists($filePath)) {
                    $this->deleteImage($image);
                }
            }
            $room->delete();
            return redirect()->route('rooms.index')->with('status', 'Room deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error in RoomController@destroy:' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }
    }

    public function showCurrnetAvailableRooms(){
        try {
            $currentDateTime = now();
    
            // gett room IDs that are currently reserved
            $reservedRoomIds = Reservation::where('start_date', '<=', $currentDateTime)
                                            ->where('end_date', '>=', $currentDateTime)
                                            ->pluck('room_id')
                                            ->toArray();
            $availableRooms = Room::whereNotIn('id', $reservedRoomIds)
                                  ->where('status', 'available')
                                  ->get();
    
            return view('Admin.pages.dashboard.rooms.index', ['rooms' => $availableRooms]);
        } catch (\Exception $e) {
            Log::error('Error in RoomController@showCurrentAvailableRooms: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }
    }

    public function showCurrnetOccupiedRoomsWithguests(){
        try{
            $allReservations = Reservation::all();
            $todayExistingReservations = $allReservations->filter(function ($reservation,$key) {
                $todayDate = date("Y-m-d");
                return ($todayDate >= $reservation->start_date && $todayDate <= $reservation->end_date );
            });
            $todayBookingsWithRoomsAndGuestsInfo=$todayExistingReservations->load('room','guests');
            //return view('Admin.pages.dashboard.rooms.index', ['rooms'=>$rooms]);
        }catch(\Exception $e){
            Log::error('Error in RoomController@showCurrnetAvailableRooms: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }

    }
    
    public function showAvailableRoomsInSpecificTime(Request $request){
        try{
            $availableRooms=[];
            $rooms=Room::all();
            $specificDate = Carbon::parse($request->input('specificDate'));
            foreach($rooms as $room)
            {
                $reservations=Reservation::where('room_id',$room->id)->get();
                $available=True;
                foreach($reservations as $reservation)
                {   
                    if($specificDate->between($reservation->start_date,$reservation->end_date))
                    {
                        $available=False;
                        break;
                    }    
                }
                if($available)
                {
                    $avaliableRooms[]=$room; 
                }
            }
            $rooms=collect($avaliableRooms); # ali comment : i am just ensure convert it to collection before send it to view (best practise)
            return view('Admin.pages.dashboard.rooms.index',['rooms'=>$rooms]);
        }catch(\Exception $e){
            Log::error('Error in RoomController@showAvailableRoomsInSpecificTime: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }
    }
        
        public function showAvailableRoomsInPeriod(DateRangeRequest $request)
        {  
           #Noura could use this time zone ( Asia/Dubai )
           # other members 'Asia/Damascus'
           # Mr.Hashim Europe/Berlin
           try{
            $latestEndDate = Carbon::now()->toDateTimeString();
            $latestEndDate =Carbon::parse($latestEndDate); # ensure date string using parsing
            // dd($latestEndDate);
            $startRange = Carbon::parse($request->input('start_range'), 'UTC')
                ->setTimezone('Asia/Baghdad');
            $endRange = $request->has('end_range') ?
                Carbon::parse($request->input('end_range'), 'UTC')
                ->setTimezone('Asia/Baghdad') : null;
            if (!$endRange) {
                $endRange = $latestEndDate;
            }

            $availableRooms = [];
            $rooms = Room::all();

            foreach ($rooms as $room) {
                $reservations = Reservation::where('room_id', $room->id)->get();
                $available = True;
                foreach ($reservations as $reservation) {
                    if (
                        $reservation->start_date <= $endRange &&
                        $reservation->end_date   >= $startRange
                    ) {
                        $available = False;
                        break;
                    }
                }
                if ($available) {
                    $avaliableRooms[] = $room;
                }
            }
            $rooms = collect($avaliableRooms);
            return view('Admin.pages.dashboard.rooms.index', ['rooms' => $rooms]);
        } catch (\Exception $e) {
            Log::error('Error in RoomController@showAvailableRoomsInPeriod: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }
        }
        
        public function showCurrnetReservedRooms()
        { 
            try {
                $currentDateTime = now();
                Log::info('Current DateTime: ' . $currentDateTime);
                $bookedRooms = Reservation::where('start_date', '<=', $currentDateTime)
                                            ->where('end_date', '>=', $currentDateTime)
                                            ->pluck('room_id')
                                            ->toArray();
                Log::info('Booked rooms: ' . implode(', ', $bookedRooms));
                $rooms = Room::whereIn('id', $bookedRooms)->get();
            return view('Admin.pages.dashboard.rooms.index',['rooms'=>$rooms]);
            }catch(\Exception $e){
                Log::error('Error in RoomController@showCurrnetReservedRooms: ' . $e->getMessage());
                return redirect()->route('rooms.index')->with('error', $e->getMessage());
            } 
        }
        
        public function showReservedRoomsInSpecificTime(Request $request){  
         try{    
            $reservedRooms=[];
            $rooms=Room::all();
            $specificDate = Carbon::parse($request->input('specificDate'));
            foreach ($rooms as $room) {
                $reservations = Reservation::where('room_id', $room->id)->get();
                $available = False;
                foreach ($reservations as $reservation) {
                    if ($specificDate->between($reservation->start_date, $reservation->end_date)) {
                        $available = True;
                        break;
                    }
                }
                if ($available) {
                    $reservedRooms[] = $room;
                }
            }
            $rooms = collect($reservedRooms);
            return view('Admin.pages.dashboard.rooms.index', ['rooms' => $rooms]);
        } catch (\Exception $e) {
            Log::error('Error in RoomController@showReservedRoomsInSpecificTime: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }
    }

    public function showReservedRoomsInPeriod(DateRangeRequest $request)
    {
        try {
            $latestEndDate = Carbon::now()->toDateTimeString();
            $latestEndDate = Carbon::parse($latestEndDate);
            $startRange = Carbon::parse($request->input('start_range'), 'UTC')
                ->setTimezone('Asia/Baghdad');
            $endRange = $request->has('end_range') ?
                Carbon::parse($request->input('end_range'), 'UTC')
                ->setTimezone('Asia/Baghdad') : null;
            if (!$endRange) {
                $endRange = $latestEndDate;
            }
            $reservedRooms = [];
            $rooms = Room::all();
            foreach ($rooms as $room) {
                $reservations = Reservation::where('room_id', $room->id)->get();
                $available = False;
                foreach ($reservations as $reservation) {
                    if (
                        $reservation->start_date <= $endRange &&
                        $reservation->end_date   >= $startRange
                    ) {
                        $available = True;
                        break;
                    }
                }
                if ($available) {
                    $reservedRooms[] = $room;
                }
            }
            $rooms = collect($reservedRooms);
            return view('Admin.pages.dashboard.rooms.index', ['rooms' => $rooms]);
        } catch (\Exception $e) {
            Log::error('Error in RoomController@showReservedRoomsInPeriod: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', $e->getMessage());
        }
    }




    public function roomsEndingIn24Hours()
    {
        $now = Carbon::now();
        $endIn24Hours = $now->copy()->addDay();

        $reservationsEndingIn24Hours = Reservation::where('end_date', '>=', $now)
            ->where('end_date', '<=', $endIn24Hours)
            ->with('room', 'user')
            ->get();

        // foreach ($reservationsEndingIn24Hours as $reservation) {
        //     event(new EndingSoonEvent($reservation));
        // }

        $rooms = $reservationsEndingIn24Hours->map(function ($reservation) {
            return $reservation->room;
        });

        return view('Admin.pages.dashboard.rooms.ending_soon', compact('rooms'));
    }
}
