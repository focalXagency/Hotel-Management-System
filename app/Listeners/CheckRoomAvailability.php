<?php

namespace App\Listeners;

use App\Events\ReservationAttempting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckRoomAvailability
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationAttempting $event)
    {
        $room = $event->room;
        $reservationStartDate = $event->reservationStartDate;

        if ($room->status != 'available' && $room->updated_at <= $reservationStartDate) {
            throw new \Exception('The selected room is not available for reservation at the specified start date and time.');
        }
    }
}
