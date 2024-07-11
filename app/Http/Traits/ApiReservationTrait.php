<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Http\Traits\ApiResponserTrait;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Api\UpdateReservationNotification;
use App\Notifications\Api\SuccessfulReservationNotification;

trait ApiReservationTrait
{
    use ApiResponserTrait;


    protected function ReservationHandle($user, $room, $request, $reservation = null)
    {
        // check all datas issues senarios or if the room id doesn't exist then return the proper response
        $validationResponse = $this->validateReservationRequest($request, $room);
        if ($validationResponse !== true) {
            return $validationResponse;
        }

        // if no issues with date then check if room is unavailable and if it is not available then return the proper response
        // notice that in case of update the reservation then I pass the reservation in calling to except it and doesn't tell the user that the room he  trying to update is unavailable 
        $roomAvailabilityData = $this->isRoomUnavailable($room->id, $request->start_date, $request->end_date, $reservation?->id);

        if ($roomAvailabilityData['roomUnavailable']) {
            return $this->errorResponse(
                'Room is not available for the selected dates',
                [
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'reservations that comes in your selected date ' => $roomAvailabilityData['reservations'],
                    'all reservation dates of the selected room' => $roomAvailabilityData['all resevations for Room']
                ],
                400
            );
        }
        // if all is fine then either update the reservation if in Reservation handle the reservation is passed or then make new reservation 
        $reservationResponse = $reservation
            ? $this->updateReservation($user, $room, $request, $reservation)
            : $this->makeNewReservation($user, $room, $request);
        // return proper response depends if it is new reservation or updating existing reservation
        return $this->successResponse(
            $reservationResponse,
            $reservation ? 'Reservation updated successfully.' : 'Reservation created successfully.',
            $reservation ? 200 : 201
        );
    }

    protected function updateReservation($user, $room, $request, $reservation)
    {
        // before updating calculate the total price of the reservation
        $days = $this->calculateDateTime($request->start_date, $request->end_date);
        $total_price = $room->price * $days;
        // call the method passing all informations needed to update the reservation
        $this->fillReservationData($reservation, $user, $room, $request, $total_price);
        $reservation->update();

        // Tuka: calling sendUserNotification to send the update reservation notification
        $notificationData = $this->sendUserNotification($user, $reservation, true);

        return $this->prepareReservationResponse($user, $reservation, $room, $days, $notificationData);
    }

    protected function makeNewReservation($user, $room, $request)
    {
        $days = $this->calculateDateTime($request->start_date, $request->end_date);
        $total_price = $room->price * $days;

        $reservation = new Reservation();
        $this->fillReservationData($reservation, $user, $room, $request, $total_price);
        $reservation->code = $this->generateUniqueReservationCode();
        $reservation->save();

        // Tuka: calling sendUserNotification to send the successful reservation notification
        $notificationData = $this->sendUserNotification($user, $reservation);

        return $this->prepareReservationResponse($user, $reservation, $room, $days, $notificationData);
    }

    // Tuka: Sending notification to the user in 2 cases
    // 1- When he uppdating his reservation details successfully
    // 2- When he complete his reservation operation successfully
    protected function sendUserNotification($user, $reservation, $isUpdate = false)
    {
        if ($reservation) {
            if ($isUpdate) {
                $notificationData = (new UpdateReservationNotification())->toArray($user);
                Notification::send($user, new UpdateReservationNotification());
            } else {
                $notificationData = (new SuccessfulReservationNotification())->toArray($user);
                Notification::send($user, new SuccessfulReservationNotification());
            }
            return $notificationData;
        }
    }

    private function fillReservationData($reservation, $user, $room, $request, $total_price)
    {
        $reservation->user_id = $user->id;
        $reservation->room()->associate($room);
        $reservation->guestNumber = $request->guestNumber;
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;
        $reservation->totalPrice = $total_price;
    }

    private function prepareReservationResponse($user, $reservation, $room, $days, $notificationData)
    {
        // the shape of response I want it to look like in Api response 
        $typeOThisRoom = $room->roomType;
        return [
            'notification' => $notificationData,
            'user_name' => $user->name,
            'reservation_code' => $reservation->code,
            'guest_number' => $reservation->guestNumber,
            'start_date' => $reservation->start_date,
            'end_date' => $reservation->end_date,
            'room_type' => $typeOThisRoom->name,
            'room_services' => $typeOThisRoom->services->pluck('name'),
            'bill_details' => [
                'RoomServices' => $typeOThisRoom->services->pluck('price'),
                'RoomTypePrice' => $typeOThisRoom->price,
                'Room Price = (Room services price + Room Type price )' => $room->price,
                'Nights of staying' => $days
            ],
            'total_price = (Room services + Room Type price) * (Nights of staying)' => $reservation->totalPrice,
        ];
    }

    protected function checkFormatDate($date)
    {
        // it would fix any form user input doesn't match with yyyy-mm-dd or oppisite order like dd-mm-yyyy
        return Carbon::parse($date)->format('Y-m-d');
    }

    protected function validateReservationRequest($request, $room)
    {
        $request->merge([
            'start_date' => $this->checkFormatDate($request->start_date),
            'end_date' => $this->checkFormatDate($request->end_date),
        ]);

        if (!$room) {
            return $this->errorResponse('Room not found', ['room_id' => null], 404);
        }

        $roomType = $room->roomType;

        if ($request->guestNumber > $roomType->capacity) {
            return $this->errorResponse('The number of guests exceeds the room capacity', ['guestNumber' => $request->guestNumber, 'Room_Capacity' => $roomType->capacity], 400);
        }

        if ($request->start_date > $request->end_date) {
            return $this->errorResponse('The start date is after the end date.', ['start_date' => $request->start_date], 400);
        }

        $currentDate = date('Y-m-d');

        if ($request->start_date < $currentDate) {
            return $this->errorResponse('The start date is in the past.', ['start_date' => $request->start_date], 400);
        }

        if ($request->end_date < $currentDate) {
            return $this->errorResponse('The end date is in the past.', ['end_date' => $request->end_date], 400);
        }

        if ($request->start_date == $request->end_date) {
            return $this->errorResponse('The start date is equal to the end date. The stay must be at least one night.', ['start_date' => $request->start_date], 400);
        }

        return true;
    }


    protected function isRoomUnavailable($room_id, $start_date, $end_date, $reservation_id = null)
    {
        $query = Reservation::where('room_id', $room_id)
            ->where(function ($query) use ($start_date, $end_date) {
                $query->whereBetween('start_date', [$start_date, $end_date])
                    ->orWhereBetween('end_date', [$start_date, $end_date]);
            });

        if ($reservation_id) {
            $query->where('id', '!=', $reservation_id);
        }

        $reservations = $query->select('start_date', 'end_date')->get();

        $roomUnavailable = $reservations->isNotEmpty();
        $allReservationForRoom = Reservation::where('room_id', $room_id)->select('start_date', 'end_date')->get();

        return [
            'roomUnavailable' => $roomUnavailable,
            'reservations' => $reservations,
            'all resevations for Room' => $allReservationForRoom,
        ];
    }

    protected function calculateDateTime($start_date, $end_date)
    {
        // calcaulating days of staying so we can calculate the total price of reservation 
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        return $start->diffInDays($end);
    }

    protected function generateUniqueReservationCode()
    {
        // make code in form starting like this A0001 and add 1 for new reservation 
        // A0002 till it becomes A9999 then becomes B0001 and so on till Z9999
        // in this case it would make a unique reservation code for every new reservation 

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
