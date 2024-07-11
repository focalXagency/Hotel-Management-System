<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationStatusCatlog>
 */
class ReservationStatusCatlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
      $name=$this->faker->randomElement(['Pending ','confirmed','inprogress','checked_in','checked_out']);
        
      return [
            'name'=>$name,
        ];
    }
}
