<?php

namespace Database\Seeders;

use App\Models\Mechanic;
use Illuminate\Database\Seeder;

class MechanicSeeder extends Seeder
{
    public function run(): void
    {
        $mechanics = [
            ['name' => 'Ahmad Rizki', 'specialist' => 'Mesin'],
            ['name' => 'Bambang Sutejo', 'specialist' => 'Kelistrikan'],
            ['name' => 'Cecep Hermawan', 'specialist' => 'Sasis & Transmisi'],
            ['name' => 'Deni Gunawan', 'specialist' => 'AC & Pendingin'],
            ['name' => 'Eko Prasetyo', 'specialist' => 'Umum'],
        ];

        foreach ($mechanics as $m) {
            Mechanic::create($m);
        }
    }
}
