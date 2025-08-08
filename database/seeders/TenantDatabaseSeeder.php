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
        // This seeder should be run on a specific tenant's database.
        // You would typically call this from a custom Artisan command, e.g.,
        // php artisan tenant:seed {hotel_id}

        // For now, we will assume that the connection has been set to the tenant's database.

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
        $guests = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Martin',
                'email' => 'jean.martin@email.com',
                'phone' => '+237 690 123 456',
                'nationality' => 'Française',
            ],
            [
                'first_name' => 'Aminata',
                'last_name' => 'Diallo',
                'email' => 'aminata.diallo@email.com',
                'phone' => '+237 691 234 567',
                'nationality' => 'Camerounaise',
            ],
        ];

        foreach ($guests as $guestData) {
            Guest::create($guestData);
        }

        // Create services
        $services = [
            ['name' => 'Petit-déjeuner', 'category' => 'restaurant', 'price' => 15000, 'pricing_type' => 'per_person'],
            ['name' => 'Transfert aéroport', 'category' => 'transport', 'price' => 25000, 'pricing_type' => 'fixed'],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }
    }
}
