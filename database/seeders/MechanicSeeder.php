<?php

namespace Database\Seeders;

use App\Models\Mechanic;
use Illuminate\Database\Seeder;

class MechanicSeeder extends Seeder
{
    public function run(): void
    {
        $mechanics = [
            ['name' => 'Ahmad Rizki', 'specialist' => 'Mesin', 'phone' => '081234567890'],
            ['name' => 'Bambang Sutejo', 'specialist' => 'Kelistrikan', 'phone' => '081234567891'],
            ['name' => 'Cecep Hermawan', 'specialist' => 'Sasis & Transmisi', 'phone' => '081234567892'],
            ['name' => 'Deni Gunawan', 'specialist' => 'AC & Pendingin', 'phone' => '081234567893'],
            ['name' => 'Eko Prasetyo', 'specialist' => 'Umum', 'phone' => '081234567894'],
        ];

        foreach ($mechanics as $m) {
            Mechanic::create($m);
        }
    }
}
