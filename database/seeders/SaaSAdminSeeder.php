<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SaaSAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'SaaS',
            'last_name' => 'Admin',
            'email' => 'admin@saas.com',
            'password' => Hash::make('password'),
            'role' => 'saas_admin',
        ]);
    }
}
