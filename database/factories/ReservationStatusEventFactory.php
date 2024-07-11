<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationStatusCatlog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationStatusEvent>
 */
class ReservationStatusEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   $reservation_ids=Reservation::pluck('id')->toArray();
        $reservation_id=$this->faker->randomElement($reservation_ids);
        $reservation_catlog_ids=ReservationStatusCatlog::pluck('id')->toArray();
        $reservation_catlog_id=$this->faker->randomElement($reservation_catlog_ids);
        return [
            'reservation_id'=>$reservation_id,
            'reservation_status_catlog_id'=>$reservation_catlog_id,

        ];
    }
}
