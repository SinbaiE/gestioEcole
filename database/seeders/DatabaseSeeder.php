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
use App\Models\ServiceBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HotelSeeder::class,
        ]);

        $hotel = Hotel::first();

        // Créer des utilisateurs
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
            $userData['hotel_id'] = $hotel->id;
            $userData['password'] = Hash::make('password');
            User::create($userData);
        }

        // Créer des types de chambres
        $roomTypes = [
            [
                'name' => 'Chambre Standard',
                'description' => 'Chambre confortable avec vue sur la ville, équipée de tout le nécessaire pour un séjour agréable',
                'base_price' => 75000,
                'max_occupancy' => 2,
                'bed_count' => 1,
                'bed_type' => 'Double',
                'room_size' => 25.0,
                'amenities' => ['wifi', 'tv', 'climatisation', 'minibar', 'coffre_fort'],
            ],
            [
                'name' => 'Chambre Deluxe',
                'description' => 'Chambre spacieuse avec balcon et vue panoramique sur la ville',
                'base_price' => 120000,
                'max_occupancy' => 3,
                'bed_count' => 1,
                'bed_type' => 'King',
                'room_size' => 35.0,
                'amenities' => ['wifi', 'tv', 'climatisation', 'minibar', 'balcon', 'coffre_fort', 'peignoir'],
            ],
            [
                'name' => 'Suite Junior',
                'description' => 'Suite avec salon séparé et espace de travail',
                'base_price' => 200000,
                'max_occupancy' => 4,
                'bed_count' => 1,
                'bed_type' => 'King',
                'room_size' => 50.0,
                'amenities' => ['wifi', 'tv', 'climatisation', 'minibar', 'balcon', 'coffre_fort', 'salon', 'bureau'],
            ],
            [
                'name' => 'Suite Présidentielle',
                'description' => 'Suite de luxe avec vue panoramique et services personnalisés',
                'base_price' => 350000,
                'max_occupancy' => 6,
                'bed_count' => 2,
                'bed_type' => 'King',
                'room_size' => 80.0,
                'amenities' => ['wifi', 'tv', 'climatisation', 'minibar', 'balcon', 'coffre_fort', 'salon', 'jacuzzi', 'service_personnel'],
            ],
        ];

        foreach ($roomTypes as $roomTypeData) {
            $roomTypeData['hotel_id'] = $hotel->id;
            RoomType::create($roomTypeData);
        }

        // Créer des chambres
        $roomTypes = RoomType::all();
        $floors = ['1', '2', '3', '4', '5'];
        $roomNumber = 101;

        foreach ($floors as $floor) {
            foreach ($roomTypes as $roomType) {
                for ($i = 1; $i <= 4; $i++) {
                    Room::create([
                        'hotel_id' => $hotel->id,
                        'room_type_id' => $roomType->id,
                        'room_number' => (string) $roomNumber,
                        'floor' => $floor,
                        'status' => collect(['available', 'occupied', 'maintenance', 'cleaning'])->random(),
                        'housekeeping_status' => collect(['clean', 'dirty', 'inspected'])->random(),
                    ]);
                    $roomNumber++;
                }
            }
        }

        // Créer des services
        $services = [
            // Spa & Bien-être
            ['name' => 'Massage relaxant', 'category' => 'spa', 'price' => 45000, 'pricing_type' => 'per_hour', 'max_capacity' => 1],
            ['name' => 'Soin du visage', 'category' => 'spa', 'price' => 35000, 'pricing_type' => 'fixed', 'max_capacity' => 1],
            ['name' => 'Manucure/Pédicure', 'category' => 'spa', 'price' => 25000, 'pricing_type' => 'fixed', 'max_capacity' => 1],
            
            // Restaurant & Bar
            ['name' => 'Petit-déjeuner buffet', 'category' => 'restaurant', 'price' => 15000, 'pricing_type' => 'per_person', 'max_capacity' => null],
            ['name' => 'Dîner gastronomique', 'category' => 'restaurant', 'price' => 35000, 'pricing_type' => 'per_person', 'max_capacity' => null],
            ['name' => 'Cocktail signature', 'category' => 'bar', 'price' => 8000, 'pricing_type' => 'fixed', 'max_capacity' => null],
            
            // Transport
            ['name' => 'Transfert aéroport', 'category' => 'transport', 'price' => 25000, 'pricing_type' => 'fixed', 'max_capacity' => 4],
            ['name' => 'Location voiture', 'category' => 'transport', 'price' => 50000, 'pricing_type' => 'per_day', 'max_capacity' => null],
            
            // Autres services
            ['name' => 'Blanchisserie express', 'category' => 'laundry', 'price' => 5000, 'pricing_type' => 'fixed', 'max_capacity' => null],
            ['name' => 'Room service', 'category' => 'room_service', 'price' => 3000, 'pricing_type' => 'fixed', 'max_capacity' => null],
            ['name' => 'Accès salle de sport', 'category' => 'fitness', 'price' => 10000, 'pricing_type' => 'per_day', 'max_capacity' => 20],
            ['name' => 'Services business', 'category' => 'business_center', 'price' => 2000, 'pricing_type' => 'per_hour', 'max_capacity' => null],
        ];

        foreach ($services as $serviceData) {
            $serviceData['hotel_id'] = $hotel->id;
            $serviceData['description'] = 'Service de qualité offert par notre établissement';
            Service::create($serviceData);
        }

        // Créer des clients de test
        $guests = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Martin',
                'email' => 'jean.martin@email.com',
                'phone' => '+237 690 123 456',
                'nationality' => 'Française',
                'guest_type' => 'individual',
                'loyalty_points' => 150,
            ],
            [
                'first_name' => 'Aminata',
                'last_name' => 'Diallo',
                'email' => 'aminata.diallo@email.com',
                'phone' => '+237 691 234 567',
                'nationality' => 'Camerounaise',
                'guest_type' => 'corporate',
                'loyalty_points' => 300,
            ],
            [
                'first_name' => 'Paul',
                'last_name' => 'Biya',
                'email' => 'paul.biya@email.com',
                'phone' => '+237 692 345 678',
                'nationality' => 'Camerounaise',
                'guest_type' => 'vip',
                'loyalty_points' => 1000,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '+1 555 123 4567',
                'nationality' => 'Américaine',
                'guest_type' => 'individual',
                'loyalty_points' => 75,
            ],
            [
                'first_name' => 'Mohammed',
                'last_name' => 'Hassan',
                'email' => 'mohammed.hassan@email.com',
                'phone' => '+237 693 456 789',
                'nationality' => 'Marocaine',
                'guest_type' => 'corporate',
                'loyalty_points' => 200,
            ],
            [
                'first_name' => 'Claire',
                'last_name' => 'Dubois',
                'email' => 'claire.dubois@email.com',
                'phone' => '+33 6 12 34 56 78',
                'nationality' => 'Française',
                'guest_type' => 'group',
                'loyalty_points' => 400,
            ],
        ];

        foreach ($guests as $guestData) {
            Guest::create($guestData);
        }

        // Créer des réservations de test
        $guests = Guest::all();
        $rooms = Room::all();
        $services = Service::all();

        foreach ($guests as $guest) {
            // Créer 2-3 réservations par client
            $reservationCount = rand(1, 3);
            
            for ($i = 0; $i < $reservationCount; $i++) {
                $room = $rooms->random();
                $checkInDate = now()->subDays(rand(1, 90))->addDays(rand(1, 30));
                $nights = rand(1, 7);
                $checkOutDate = $checkInDate->copy()->addDays($nights);
                
                $reservation = Reservation::create([
                    'reservation_number' => 'RES-' . strtoupper(uniqid()),
                    'hotel_id' => $hotel->id,
                    'guest_id' => $guest->id,
                    'room_type_id' => $room->room_type_id,
                    'room_id' => $room->id,
                    'check_in_date' => $checkInDate,
                    'check_out_date' => $checkOutDate,
                    'adults' => rand(1, 3),
                    'children' => rand(0, 2),
                    'nights' => $nights,
                    'room_rate' => $room->roomType->base_price,
                    'total_amount' => $room->roomType->base_price * $nights,
                    'paid_amount' => rand(0, 1) ? $room->roomType->base_price * $nights : 0,
                    'status' => collect(['confirmed', 'checked_in', 'checked_out'])->random(),
                    'payment_status' => collect(['pending', 'paid'])->random(),
                    'source' => collect(['direct', 'booking.com', 'expedia', 'phone', 'walk_in'])->random(),
                ]);

                // Créer des réservations de services
                if (rand(0, 1)) {
                    $serviceCount = rand(1, 3);
                    for ($j = 0; $j < $serviceCount; $j++) {
                        $service = $services->random();
                        $quantity = rand(1, 2);
                        
                        ServiceBooking::create([
                            'hotel_id' => $hotel->id,
                            'service_id' => $service->id,
                            'guest_id' => $guest->id,
                            'reservation_id' => $reservation->id,
                            'booking_number' => 'SRV-' . strtoupper(uniqid()),
                            'service_date' => $checkInDate->copy()->addDays(rand(0, $nights - 1)),
                            'service_time' => now()->setTime(rand(8, 20), rand(0, 59)),
                            'quantity' => $quantity,
                            'guests_count' => rand(1, $reservation->adults),
                            'unit_price' => $service->price,
                            'total_amount' => $service->price * $quantity,
                            'status' => collect(['confirmed', 'completed'])->random(),
                        ]);
                    }
                }

                // Créer une facture pour les réservations terminées
                if ($reservation->status === 'checked_out') {
                    $serviceBookings = ServiceBooking::where('reservation_id', $reservation->id)->get();
                    $serviceTotal = $serviceBookings->sum('total_amount');
                    
                    $lineItems = [
                        [
                            'description' => 'Hébergement - ' . $room->roomType->name,
                            'quantity' => $nights,
                            'unit_price' => $room->roomType->base_price,
                            'total' => $reservation->total_amount,
                        ]
                    ];
                    
                    foreach ($serviceBookings as $serviceBooking) {
                        $lineItems[] = [
                            'description' => $serviceBooking->service->name,
                            'quantity' => $serviceBooking->quantity,
                            'unit_price' => $serviceBooking->unit_price,
                            'total' => $serviceBooking->total_amount,
                        ];
                    }
                    
                    $subtotal = $reservation->total_amount + $serviceTotal;
                    $taxAmount = $subtotal * 0.1925; // TVA Cameroun
                    $totalAmount = $subtotal + $taxAmount;
                    
                    $invoice = Invoice::create([
                        'invoice_number' => 'INV-' . strtoupper(uniqid()),
                        'hotel_id' => $hotel->id,
                        'reservation_id' => $reservation->id,
                        'guest_id' => $guest->id,
                        'subtotal' => $subtotal,
                        'tax_amount' => $taxAmount,
                        'discount_amount' => 0,
                        'total_amount' => $totalAmount,
                        'status' => collect(['sent', 'paid'])->random(),
                        'due_date' => $checkOutDate->copy()->addDays(30),
                        'line_items' => $lineItems,
                        'paid_at' => rand(0, 1) ? $checkOutDate->copy()->addDays(rand(1, 15)) : null,
                    ]);

                    // Créer un paiement si la facture est payée
                    if ($invoice->status === 'paid') {
                        Payment::create([
                            'hotel_id' => $hotel->id,
                            'invoice_id' => $invoice->id,
                            'guest_id' => $guest->id,
                            'payment_number' => 'PAY-' . strtoupper(uniqid()),
                            'amount' => $totalAmount,
                            'payment_method' => collect(['cash', 'card', 'orange_money', 'mtn_mobile_money'])->random(),
                            'status' => 'completed',
                            'payment_date' => $invoice->paid_at,
                            'transaction_reference' => 'TXN-' . strtoupper(uniqid()),
                        ]);
                    }
                }
            }
        }

        // Mettre à jour les points de fidélité et derniers séjours
        foreach ($guests as $guest) {
            $completedReservations = $guest->reservations()->where('status', 'checked_out')->get();
            $totalSpent = $completedReservations->sum('total_amount');
            $loyaltyPoints = intval($totalSpent / 1000); // 1 point par 1000 FCFA
            $lastStay = $completedReservations->max('check_out_date');
            
            $guest->update([
                'loyalty_points' => $loyaltyPoints,
                'last_stay' => $lastStay,
            ]);
        }

        $this->command->info('Base de données peuplée avec succès !');
        $this->command->info('Utilisateurs créés :');
        $this->command->info('- Admin: admin@hotel.com (password)');
        $this->command->info('- Réceptionniste: receptionist@hotel.com (password)');
        $this->command->info('- Manager: manager@hotel.com (password)');
        $this->command->info('- Comptable: accountant@hotel.com (password)');
    }
}
