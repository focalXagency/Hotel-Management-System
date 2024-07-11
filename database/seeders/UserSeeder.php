<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), 
        ]);
        $user->assignRole('admin');

        User::factory(4)->create()->each(function ($user) {
            $randomRole = rand(0, 1) ? 'admin' : 'client'; 
            $user->assignRole($randomRole);
        });
    }
}
