<?php

namespace Database\Seeders;

use App\Models\ReservationStatusEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationStatusEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReservationStatusEvent::factory(4)->create();
    }
}
