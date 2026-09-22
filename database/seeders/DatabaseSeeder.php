<?php

namespace Database\Seeders;

use App\Models\User;
use \App\Models\Room;
use App\Models\RoomModel as RoomPhoto;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Staff',
            'email' => 'test@example.com',
            'password' => 'staff', // password is 'staff'
        ]);
        User::factory()->create([
            'name' => 'StaffUser',
            'email' => 'staff@example.com',
            'password' => 'staff', // password is 'staff'
        ]);

        // create 1 room
        Room::factory()->create([
            'rm_number' => '101',
            'rm_type' => 'Single',
            'status' => 'available',
            'rm_price_per_night' => 100.00,
        ]);
        // room photos
        RoomPhoto::factory()->create([
            'room_id' => 1,
            'photo_path' => 'room-photos/roomA_01.jpg',
        ]);
        RoomPhoto::factory()->create([
            'room_id' => 1,
            'photo_path' => 'room-photos/roomA_02.jpg',
        ]);
        Room::factory()->create([
            'rm_number' => '102',
            'rm_type' => 'Double',
            'status' => 'available',
            'rm_price_per_night' => 150.00,
        ]);

        // \App\Models\Room::factory(20)->create();
        // \App\Models\Guest::factory(50)->create();
        // \App\Models\Reservation::factory(30)->create();
        // \App\Models\Payment::factory(30)->create();
    }
}
