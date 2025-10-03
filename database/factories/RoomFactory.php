<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
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
            'rm_number' => $this->faker->unique()->numberBetween(100, 999),
            'rm_type' => $this->faker->randomElement(['single', 'double', 'suite']),
            'rm_price_per_night' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['available', 'booked', 'maintenance']),
        ];
    }
}
