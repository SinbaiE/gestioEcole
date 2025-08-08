<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create users
        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'Hotel',
                'email' => 'admin@hotel.com',
                'role' => 'hotel_admin',
                'permissions' => ['all'],
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Dupont',
                'email' => 'receptionist@hotel.com',
                'role' => 'receptionist',
                'permissions' => ['reservations', 'guests', 'rooms', 'invoices'],
            ],
            [
                'first_name' => 'Jean',
                'last_name' => 'Manager',
                'email' => 'manager@hotel.com',
                'role' => 'manager',
                'permissions' => ['reservations', 'guests', 'rooms', 'invoices', 'reports', 'services'],
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Comptable',
                'email' => 'accountant@hotel.com',
                'role' => 'accountant',
                'permissions' => ['invoices', 'payments', 'reports'],
            ],
        ];

        foreach ($users as $userData) {
            $userData['password'] = Hash::make('password');
            User::create($userData);
        }

        // Create room types
        $roomTypes = [
            [
                'name' => 'Chambre Standard',
                'description' => 'Chambre confortable avec vue sur la ville',
                'base_price' => 75000,
                'max_occupancy' => 2,
                'bed_count' => 1,
                'bed_type' => 'Double',
                'room_size' => 25.0,
                'amenities' => ['wifi', 'tv', 'climatisation'],
            ],
            [
                'name' => 'Chambre Deluxe',
                'description' => 'Chambre spacieuse avec balcon',
                'base_price' => 120000,
                'max_occupancy' => 3,
                'bed_count' => 1,
                'bed_type' => 'King',
                'room_size' => 35.0,
                'amenities' => ['wifi', 'tv', 'climatisation', 'balcon'],
            ],
            [
                'name' => 'Suite Junior',
                'description' => 'Suite avec salon séparé',
                'base_price' => 200000,
                'max_occupancy' => 4,
                'bed_count' => 1,
                'bed_type' => 'King',
                'room_size' => 50.0,
                'amenities' => ['wifi', 'tv', 'climatisation', 'salon'],
            ],
        ];

        foreach ($roomTypes as $roomTypeData) {
            RoomType::create($roomTypeData);
        }

        // Create rooms
        $roomTypes = RoomType::all();
        $roomNumber = 101;
        foreach ($roomTypes as $roomType) {
            for ($i = 0; $i < 5; $i++) {
                Room::create([
                    'room_type_id' => $roomType->id,
                    'room_number' => (string) $roomNumber++,
                    'floor' => '1',
                    'status' => 'available',
                    'housekeeping_status' => 'clean',
                ]);
            }
        }

        // Create guests
        Guest::factory()->count(50)->create();

        // Create services
        $services = [
            ['name' => 'Petit-déjeuner', 'category' => 'restaurant', 'price' => 15000, 'pricing_type' => 'per_person'],
            ['name' => 'Transfert aéroport', 'category' => 'transport', 'price' => 25000, 'pricing_type' => 'fixed'],
            ['name' => 'Massage relaxant', 'category' => 'spa', 'price' => 45000, 'pricing_type' => 'per_hour'],
            ['name' => 'Blanchisserie', 'category' => 'laundry', 'price' => 5000, 'pricing_type' => 'fixed'],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }
    }
}
