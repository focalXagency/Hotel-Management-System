<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use App\Models\ReservationStatusEvent;
use App\Http\Requests\StoreReservationStatusEventRequest;
use App\Http\Requests\UpdateReservationStatusEventRequest;

class ReservationStatusEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationStatusEventRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservationStatusEvent $reservationStatusEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservationStatusEvent $reservationStatusEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationStatusEventRequest $request, ReservationStatusEvent $reservationStatusEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservationStatusEvent $reservationStatusEvent)
    {
        //
    }

    
}
