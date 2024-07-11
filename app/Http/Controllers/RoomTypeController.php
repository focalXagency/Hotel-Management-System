<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;
use App\Models\Service;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roomsType = RoomType::all();
        return view('Admin.pages.dashboard.room_types.index', compact('roomsType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        return view('Admin.pages.dashboard.room_types.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomTypeRequest $request)
    {
        try {
            $request->validated();

            $new_room_type = RoomType::create($request->only([
                'name', 
                'price', 
                'capacity', 
                'description'
            ]));
            $new_room_type->services()->attach($request->service_id);
            return redirect()->route('roomType.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $roomType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomType $roomType)
    {
        $services = Service::all();
        return view('Admin.pages.dashboard.room_types.edit', compact('roomType','services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        try {
            $request->validated();
            $roomType->update($request->only([
                'name', 
                'price', 
                'capacity', 
                'description'
            ]));
            $roomType->services()->sync($request->service_id);
           
            return redirect()->route('roomType.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return redirect()->route('roomType.index');
    }
}
