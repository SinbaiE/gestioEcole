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
            'payment_method' => fake()->randomElement(['cash', 'card', 'orange_money']),
            'status' => 'completed',
            'payment_date' => now(),
        ];
    }
}
