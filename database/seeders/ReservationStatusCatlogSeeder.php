<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationStatusCatlog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReservationStatusCatlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReservationStatusCatlog::factory(4)->create();
    }
}
