<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hotels')->delete();

        Hotel::create([
            'name' => 'Hotel Paradise',
            'subdomain' => 'paradise',
            'description' => 'A beautiful hotel by the beach.',
            'address' => '123 Paradise Lane',
            'city' => 'Beachville',
            'country' => 'Wonderland',
        ]);

        Hotel::create([
            'name' => 'Hotel Ocean View',
            'subdomain' => 'oceanview',
            'description' => 'A hotel with a stunning ocean view.',
            'address' => '456 Ocean Drive',
            'city' => 'Seaside',
            'country' => 'Wonderland',
        ]);

        Hotel::create([
            'name' => 'Mountain Retreat',
            'subdomain' => 'mountainretreat',
            'description' => 'A cozy hotel in the mountains.',
            'address' => '789 Mountain Pass',
            'city' => 'Hilltop',
            'country' => 'Wonderland',
        ]);
    }
}
