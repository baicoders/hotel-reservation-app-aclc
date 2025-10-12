<?php

namespace Database\Seeders;

use App\Models\User;
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
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'test', // password is 'test'
        ]);
        User::factory()->create([
            'name' => 'StaffUser',
            'email' => 'staff@example.com',
            'password' => 'staff', // password is 'staff'
        ]);

        \App\Models\Room::factory(20)->create();
        \App\Models\Guest::factory(50)->create();
        \App\Models\Reservation::factory(30)->create();
        \App\Models\Payment::factory(30)->create();
    }
}
