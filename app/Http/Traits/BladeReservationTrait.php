<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Reservation;
use App\Events\ReservationAttempting;
use Illuminate\Support\Facades\Redirect;

trait BladeReservationTrait 
{
    protected function ReservationHandle($request)
    { 
        $validationResult = $this->validateReservationRequest($request);
        if ($validationResult !== true) {
            return $validationResult; // Return the redirect response if validation fails
        }
       
        $roomAvailabilityData = $this->isRoomUnavailable($request->room_id, $request->start_date, $request->end_date);
        if ($roomAvailabilityData['roomUnavailable']) {
            return redirect()->back()->withErrors(['room_id' => 'Sorry, the room is not available for the selected dates.'])->withInput();
        }

        // Fetch the room instance
        $room = Room::find($request->room_id);
        if (!$room) {
            return redirect()->back()->withErrors(['room_id' => 'Room not found.'])->withInput();
        }

        $room_price = $room->price;
        $days = $this->CalculateDateTime($request->start_date, $request->end_date);
        $total_price = $room_price * $days;

        // Making new Reservation after the request has been validated
        $reservation = new Reservation();
        $reservation->user_id = $request->user_id;
        $reservation->room_id = $request->room_id;
        $reservation->code = $this->generateUniqueReservationCode();
        $reservation->guestNumber = $request->guestNumber;
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;
        $reservation->totalPrice = $total_price;
        $reservation->save();

        // Trigger the event to check room availability before making the reservation
        event(new ReservationAttempting($room, Carbon::parse($request->start_date)));

        return redirect()->route('reservation.index')->with('success', 'Reservation created successfully.');
    }

    protected function validateReservationRequest($request)
    {
         $room = Room::find($request->room_id);
    $room_capacity = $room->roomType->capacity;

    if ($request->guestNumber > $room_capacity) {
        return redirect()->route('reservation.create')
            ->withErrors(['guestNumber' => 'The number of guests exceeds the room capacity of ' . $room_capacity . '.'])
            ->withInput();
    }
        if ($request->start_date > $request->end_date) {
            return redirect()->route('reservation.create')
                ->withErrors(['start_date' => 'The start date cannot be after the end date.'])
                ->withInput();
        }

        $currentDate = date('Y-m-d');

        if ($request->start_date < $currentDate) {
            return redirect()->route('reservation.create')
                ->withErrors(['start_date' => 'The start date cannot be in the past.'])
                ->withInput();
        }

        if ($request->end_date < $currentDate) {
            return redirect()->route('reservation.create')
                ->withErrors(['end_date' => 'The end date cannot be in the past.'])
                ->withInput();
        }

        if ($request->start_date == $request->end_date) {
            return redirect()->route('reservation.create')
                ->withErrors(['start_date' => 'The start date must be different from the end date.'])
                ->withInput();
        }

        return true;
    }

    protected function isRoomUnavailable($room_id, $start_date, $end_date)
    {
        $reservations = Reservation::where('room_id', $room_id)
            ->where(function ($query) use ($start_date, $end_date) {
                $query->whereBetween('start_date', [$start_date, $end_date])
                    ->orWhereBetween('end_date', [$start_date, $end_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$start_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$end_date]);
            })
            ->get();

        $roomUnavailable = $reservations->isNotEmpty();

        return [
            'roomUnavailable' => $roomUnavailable,
            'reservations' => $reservations,
        ];
    }

    protected function CalculateDateTime($start_date, $end_date)
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        $daysDifference = $start->diffInDays($end);
        return $daysDifference;
    }

    protected function generateUniqueReservationCode()
    {
        $letters = range('A', 'Z');
        $letterIndex = 0;
        $number = 1;

        while (true) {
            $code = $letters[$letterIndex] . str_pad($number, 4, '0', STR_PAD_LEFT);

            if (!Reservation::where('code', $code)->exists()) {
                return $code;
            }

            $number++;

            if ($number > 9999) {
                $letterIndex++;
                $number = 1;

                if ($letterIndex >= count($letters)) {
                    throw new \Exception('Maximum number of reservation codes reached');
                }
            }
        }
    }

    public function roomsEndingIn24Hours($query)
    {
        $now = Carbon::now();
        $endIn24Hours = $now->copy()->addDay();

        return $query->where('end_date', '>=', $now)
                     ->where('end_date', '<=', $endIn24Hours);
    }
}
