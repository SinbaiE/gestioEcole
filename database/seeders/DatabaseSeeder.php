<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the SaaS admins
        $this->call(SaaSAdminSeeder::class);

        // Seed the hotels
        $this->call(HotelSeeder::class);

        // Get all hotels
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            $this->command->info("Seeding data for hotel: {$hotel->name}");

            // Create users for this hotel
            User::factory()->count(20)->create(['hotel_id' => $hotel->id]);
            User::factory()->create([
                'hotel_id' => $hotel->id,
                'first_name' => 'Admin',
                'last_name' => 'Hotel',
                'email' => 'admin@' . $hotel->subdomain . '.com',
                'role' => 'hotel_admin',
                'password' => Hash::make('password'),
            ]);

            // Create room types for this hotel
            $roomTypes = RoomType::factory()->count(10)->create(['hotel_id' => $hotel->id]);

            // Create rooms for this hotel
            $rooms = collect();
            foreach ($roomTypes as $roomType) {
                $rooms = $rooms->merge(
                    Room::factory()->count(20)->create([
                        'hotel_id' => $hotel->id,
                        'room_type_id' => $roomType->id,
                    ])
                );
            }

            // Create services for this hotel
            Service::factory()->count(25)->create(['hotel_id' => $hotel->id]);

            // Create guests for this hotel
            $guests = Guest::factory()->count(500)->create(['hotel_id' => $hotel->id]);

            // Create reservations, invoices, and payments
            $guests->each(function ($guest) use ($hotel, $rooms) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    $room = $rooms->random();
                    $roomType = $room->roomType;
                    $nights = rand(1, 14);
                    $roomRate = $roomType->base_price;
                    $totalAmount = $nights * $roomRate;

                    $reservation = Reservation::factory()->create([
                        'hotel_id' => $hotel->id,
                        'guest_id' => $guest->id,
                        'room_id' => $room->id,
                        'room_type_id' => $roomType->id,
                        'nights' => $nights,
                        'room_rate' => $roomRate,
                        'total_amount' => $totalAmount,
                    ]);

                    // Create an invoice for completed reservations
                    if ($reservation->status === 'checked_out') {
                        $invoice = Invoice::factory()->create([
                            'hotel_id' => $hotel->id,
                            'reservation_id' => $reservation->id,
                            'guest_id' => $guest->id,
                            'total_amount' => $reservation->total_amount,
                        ]);

                        // Create a payment for paid invoices
                        if ($invoice->status === 'paid') {
                            Payment::factory()->create([
                                'hotel_id' => $hotel->id,
                                'invoice_id' => $invoice->id,
                                'guest_id' => $guest->id,
                                'amount' => $invoice->total_amount,
                            ]);
                        }
                    }
                }
            });
        }
    }
}
