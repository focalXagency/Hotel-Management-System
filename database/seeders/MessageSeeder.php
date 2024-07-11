<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::create([
            'contact_id'=>'1',
            'title'=>'Room Availability Inquiry',
            'body'=>"I hope you're doing well. I'm interested in booking a room for a two-night stay from July 15th to July 17th. Could you please confirm availability and provide rates?"
        ]);
        Message::create([
            'contact_id'=>'1',
            'title'=>'Availability',
            'body'=>"Hi, I hope you're doing well. I'm interested in booking a room for a two-night stay from July 15th to July 17th. Could you please confirm availability and provide rates?"
        ]);
    }
}
