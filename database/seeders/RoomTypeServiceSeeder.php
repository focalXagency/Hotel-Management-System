<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomTypeServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = RoomType::all();


        $servicesForRoomTypes = [
            'Standard Single Room' => ['Single Bed', 'Breakfast'],
            'Standard Suite ' => ['Double Bed', 'TV', 'Breakfast', 'Indoor Swimming Pool'],
            'VIP Single Room' => ['King-size Bed', 'TV', 'Fitness Center', 'Outdoor Swimming Pool', 'Breakfast', 'Dinner'],
            'VIP Suite ' => ['King-size Bed', 'TV', 'Fitness Center', 'Outdoor Swimming Pool', 'Breakfast', 'Dinner'],
        ];

        foreach ($roomTypes as $roomType) {
            if (isset($servicesForRoomTypes[$roomType->name])) {
                $serviceNames = $servicesForRoomTypes[$roomType->name];
                $services = Service::whereIn('name', $serviceNames)->get();
                $roomType->services()->attach($services);
            }
        }
    }
}
