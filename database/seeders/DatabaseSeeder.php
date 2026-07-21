<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@bengkel.test',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@bengkel.test',
            'password' => 'password',
        ]);

        $this->call([
            CategorySeeder::class,
            ServiceSeeder::class,
            BrandPartnerSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            VehicleSeeder::class,
            MechanicSeeder::class,
            RepairOrderSeeder::class,
        ]);
    }
}
