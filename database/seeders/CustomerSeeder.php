<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Andi Pratama', 'Budi Santoso', 'Citra Dewi', 'Dedi Kurniawan', 'Eka Putri',
            'Fajar Hidayat', 'Gita Permata', 'Hadi Susanto', 'Intan Nurjanah', 'Joko Widodo',
            'Kiki Amalia', 'Lukman Hakim', 'Mega Sari', 'Nano Hermawan', 'Olivia Tan',
            'Panji Saputra', 'Rina Marlina', 'Soleh Iskandar', 'Tina Lestari', 'Ujang Komarudin',
        ];

        foreach ($names as $i => $name) {
            Customer::create([
                'name' => $name,
                'phone' => '08' . rand(100000000, 999999999),
                'address' => fake()->address(),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@email.com',
            ]);
        }
    }
}
