<?php

namespace Database\Factories;

use App\Models\Guest;
use App\Models\Room;
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
        $checkInDate = fake()->dateTimeBetween('-1 year', '+1 month');
        $nights = fake()->numberBetween(1, 14);
        $checkOutDate = (clone $checkInDate)->modify("+$nights days");
        $roomRate = fake()->numberBetween(50, 300) * 1000;

        return [
            'reservation_number' => 'RES-' . strtoupper(uniqid()),
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'nights' => $nights,
            'adults' => fake()->numberBetween(1, 4),
            'children' => fake()->numberBetween(0, 3),
            'status' => fake()->randomElement(['confirmed', 'checked_in', 'checked_out', 'cancelled']),
            'room_rate' => $roomRate,
            'total_amount' => $nights * $roomRate,
            'paid_amount' => 0,
            'payment_status' => 'pending',
            'source' => 'direct',
        ];
    }
}
