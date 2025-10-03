<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            'reservation_id' => \App\Models\Reservation::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 1000),
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}
