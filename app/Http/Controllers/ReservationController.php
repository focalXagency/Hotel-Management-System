<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use App\Events\ReservationAttempting;
use App\Models\ReservationStatusEvent;
use App\Http\Traits\BladeReservationTrait;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;

class ReservationController extends Controller
{
    use BladeReservationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reservations = Reservation::all();
            return view('Admin.pages.dashboard.reservation.index', compact('reservations'));
        } catch (\Exception $e) {

            Log::error('Error in ReservationController@index: ' . $e->getMessage());
            return redirect()->route('reservation.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $users = User::all();
            $rooms = Room::all();
            return view('admin.pages.dashboard.reservation.create', compact('users', 'rooms'));
        } catch (\Exception $e) {
            Log::error('Error in ReservationController@create: ' . $e->getMessage());
            return redirect()->route('reservation.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $request->validated();

        // Calling the ReservationHandle method from the trait
        $response = $this->ReservationHandle($request);

        // Return the response from the trait method
        if ($response) {
            return $response;
        }
        return redirect()->route('reservation.index')->with('success', 'Reservation created successfully.');
        // $roomId = $request->input('room_id');
        // $reservationStartDate = Carbon::parse($request->input('start_date'));
        // $room = Room::findOrFail($roomId);

        // // trigger  the event to check room availability before make the reservation.

        // event(new ReservationAttempting($room, $reservationStartDate));
        // return view('reservation.index');
    }

    /**
     * Display the specified resource.
     */



    public function show(Reservation $reservation)
    {
        try {
            $stayingNights = $this->CalculateDateTime($reservation->start_date, $reservation->end_date);
            $services = $reservation->room->roomType->services->map(function ($service) {
                return [
                    'name' => $service->name,
                    'price' => $service->price,
                ];
            });

            /// edit this fucntion by ali for add  the events for this reservation (on-click)
            $reservationEvents = ReservationStatusEvent::with('reservationStatusCatalogs')
                ->where('reservation_id', $reservation->id)
                ->get();
            // dd($reservationEvents);

            $reservationStatusOverTime = [];

            foreach ($reservationEvents as $reservationEvent) {
                $reservationCurrentStatus = optional($reservationEvent->reservationStatusCatalogs)->name;
                $reservationCurrentEventDate = $reservationEvent->created_at->format('d-m-Y H:i:s');
                $reservationStatusOverTime[] = [
                    'currentStatus' => $reservationCurrentStatus ?? 'UnKnown',
                    'currentEventDate' => $reservationCurrentEventDate,
                ];
            }
            if (empty($reservationStatusOverTime)) {
                $reservationStatusOverTime[] = [
                    'currentStatus' => 'inprogress',
                    'currentEventDate' => now()->format('d-m-Y H:i:s')
                ];
            }
            return view('Admin.pages.dashboard.reservation.show', compact('reservation', 'stayingNights', 'services', 'reservationStatusOverTime'));
        } catch (\Exception $e) {

            Log::error('Error in ReservationController@show: ' . $e->getMessage());
            return redirect()->route('reservation.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
         $users = User::all();
         $rooms = Room::all();
         return view('admin.pages.dashboard.reservation.edit', compact('reservation', 'users', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {

        $reservation->update($request->validated());
        return redirect()->route('reservation.index')->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservation.index')->with('success', 'Reservation deleted successfully.');
    }
}
