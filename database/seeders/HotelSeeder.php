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
            'db_database' => 'hotel_paradise',
            'db_username' => 'root',
            'db_password' => '',
        ]);

        Hotel::create([
            'name' => 'Hotel Ocean View',
            'subdomain' => 'oceanview',
            'db_database' => 'hotel_oceanview',
            'db_username' => 'root',
            'db_password' => '',
        ]);
    }
}
