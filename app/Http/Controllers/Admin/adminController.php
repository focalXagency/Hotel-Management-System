<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class adminController extends Controller
{
    public function index()
    {   
        $currentDate = Carbon::now();

        // Fetch necessary data
        $availableRooms = Room::where('status', 'available')->count();
        $maintenanceRooms = Room::where('status', 'unavailable')->count();

        $upcomingReservations = Reservation::whereDate('start_date', '>=', $currentDate)
                                        ->orderBy('start_date', 'asc')
                                        ->take(10)
                                        ->get();
        // Fetch current reservations
        $currentReservations = Reservation::where([
                ['start_date','<=', $currentDate->format('Y-m-d') ],
                ['end_date', '>=', $currentDate->format('Y-m-d')]
                ])->get();
        // Eager load guests and room relationships
        $currentReservations->load(['room','guests']);
        // Extract occupied rooms
        $occupiedRooms = $currentReservations->pluck('room')->unique('id')->count();
        // Extract unique guests using collection methods
        $currentGuests = $currentReservations->pluck('guests')->flatten()->unique('id');
        // Extract unique guests using an Explicit Loop
        // $currentGuests = collect();
        // foreach ($currentReservations as $reservation) {
        //     $currentGuests = $currentGuests->merge($reservation->guests);
        // }
        // Remove duplicate guests, if any
        //$currentGuests = $currentGuests->unique('id');
        // $roomsWithGuests = $currentReservations->mapToGroups(function ($reservation) {
        //     return [$reservation->room->code => $reservation->guests];
        // })->map(function ($guests) {
        //     return $guests->flatten()->unique('id');
        // });

        // Map guests to their respective rooms
        $currentGuestsWithRoom = collect();
        foreach ($currentReservations as $reservation) {
            foreach ($reservation->guests as $guest) {
                $currentGuestsWithRoom->push([
                    'guest' => $guest->name,
                    'room' => $reservation->room->code
                ]);
            }
        }

        $dailyRevenue = Reservation::whereDate('created_at', $currentDate->format('Y-m-d'))
                                ->sum('totalPrice');
        // Pass data to the view
        return view('Admin.pages.Dashboard.index', compact(
            'occupiedRooms',
            'availableRooms',
            'maintenanceRooms',
            'upcomingReservations',
            'currentGuestsWithRoom',
            'dailyRevenue',
        ));
    }
}
