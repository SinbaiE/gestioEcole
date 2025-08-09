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
            'payment_number' => 'PAY-' . strtoupper(uniqid()),
            'amount' => fake()->numberBetween(10000, 500000),
            'payment_method' => fake()->randomElement(['cash', 'card', 'orange_money']),
            'status' => 'completed',
            'payment_date' => now(),
        ];
    }
}
