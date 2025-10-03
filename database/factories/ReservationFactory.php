<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'room_id' => \App\Models\Room::factory(),
            'guest_id' => \App\Models\Guest::factory(),
            'check_in_date' => $this->faker->dateTimeBetween('+1 days', '+1 month')->format('Y-m-d'),
            'check_out_date' => $this->faker->dateTimeBetween('+2 days', '+2 months')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'checked_in', 'checked_out', 'canceled']),
        ];
    }
}
