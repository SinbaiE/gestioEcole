<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Hotel;
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
            // Create users for this hotel
            User::factory()->count(10)->create(['hotel_id' => $hotel->id]);
            User::factory()->create([
                'hotel_id' => $hotel->id,
                'first_name' => 'Admin',
                'last_name' => 'Hotel',
                'email' => 'admin@' . $hotel->subdomain . '.com',
                'role' => 'hotel_admin',
                'password' => Hash::make('password'),
            ]);

            // Create room types for this hotel
            $roomTypes = RoomType::factory()->count(5)->create(['hotel_id' => $hotel->id]);

            // Create rooms for this hotel
            foreach ($roomTypes as $roomType) {
                Room::factory()->count(10)->create([
                    'hotel_id' => $hotel->id,
                    'room_type_id' => $roomType->id,
                ]);
            }

            // Create services for this hotel
            Service::factory()->count(15)->create(['hotel_id' => $hotel->id]);

            // Create guests for this hotel
            Guest::factory()->count(100)->create(['hotel_id' => $hotel->id]);
        }
    }
}
