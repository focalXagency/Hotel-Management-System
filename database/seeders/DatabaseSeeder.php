<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\AdminSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reservation::factory(10)->create();
        //  User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@example.com',
        // ]);
        //$this->call(RoleSeeder::class);
        //$this->call(AdminSeeder::class);
        //$this->call(ContactSeeder::class);

        // $this->call(RoleSeeder::class);
        // $this->call(AdminSeeder::class);
        // $this->call(ContactSeeder::class);
        // $this->call(MessageSeeder::class);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ContactSeeder::class,
            MessageSeeder::class,
            ServicesSeeder::class,
            RoomTypeSeeder::class,
            RoomTypeServiceSeeder::class,
            RoomSeeder::class,
            ReservationStatusCatlogSeeder::class,
            ReservationSeeder::class,
            ReservationStatusEventSeeder::class,

        ]);
    }
}

