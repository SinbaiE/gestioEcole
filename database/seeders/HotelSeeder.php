<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hotel::create([
            'name' => 'Grand Hôtel Yaoundé',
            'slug' => 'grand-hotel-yaounde',
            'description' => 'Un hôtel de luxe au cœur de Yaoundé offrant des services exceptionnels',
            'address' => 'Avenue Kennedy, Centre-ville',
            'city' => 'Yaoundé',
            'country' => 'Cameroun',
            'phone' => '+237 222 123 456',
            'email' => 'contact@grandhotel-yaounde.com',
            'website' => 'https://grandhotel-yaounde.com',
            'star_rating' => 5,
            'amenities' => json_encode(['wifi', 'piscine', 'spa', 'restaurant', 'bar', 'salle_sport', 'parking', 'climatisation']),
        ]);
    }
}
