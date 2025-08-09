<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(10000, 500000);
        $tax = $subtotal * 0.1; // Example 10% tax
        $total = $subtotal + $tax;

        return [
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'status' => fake()->randomElement(['paid', 'sent', 'draft', 'overdue']),
            'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'discount_amount' => 0,
            'total_amount' => $total,
            'line_items' => '[]', // Default to empty JSON array
        ];
    }
}
