<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected static $lastReservationCode = 'A0000';

    /**
     * Generate a unique reservation code.
     *
     * @return string
     */
    protected function generateUniqueReservationCode()
    {
        $letters = range('A', 'Z');
        $letterIndex = array_search(self::$lastReservationCode[0], $letters);
        $number = (int)substr(self::$lastReservationCode, 1);

        while (true) {
            $number++;
            if ($number > 9999) {
                $letterIndex++;
                $number = 1;
            }

            if ($letterIndex >= count($letters)) {
                throw new \Exception('Maximum number of reservation codes reached');
            }

            $code = $letters[$letterIndex] . str_pad($number, 4, '0', STR_PAD_LEFT);

            if (!Reservation::where('code', $code)->exists()) {
                self::$lastReservationCode = $code;
                return $code;
            }
        }
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_ids = User::pluck('id')->toArray();
        $user_id = $this->faker->randomElement($user_ids);
        $room_ids = Room::pluck('id')->toArray();
        $room_id = $this->faker->randomElement($room_ids);
        $roomPrice = Room::firstWhere('id', $room_id)->price;
        $startDate = $this->faker->dateTimeBetween('-1 week', 'now');
        $endDate = $this->faker->dateTimeBetween($startDate, Carbon::parse($startDate)->addWeek()->toDateTime());
        $days = Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate));
        $totalPrice = $roomPrice * $days;

        return [
            'user_id' => $user_id,
            'room_id' => $room_id,
            'code' => $this->generateUniqueReservationCode(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'guestNumber' => $this->faker->numberBetween(1, 2),
            'totalPrice' => $totalPrice,
        ];
    }
}
