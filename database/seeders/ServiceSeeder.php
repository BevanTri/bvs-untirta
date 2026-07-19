<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Service Mesin Bugar', 'price' => 150000],
            ['name' => 'Ganti Oli', 'price' => 45000],
            ['name' => 'Service Rem', 'price' => 75000],
            ['name' => 'Service Kopling', 'price' => 100000],
            ['name' => 'Service Rantai', 'price' => 50000],
            ['name' => 'Tune Up', 'price' => 200000],
        ];
        foreach ($services as $s) {
            Service::create([
                'name' => $s['name'],
                'slug' => str()->slug($s['name']),
                'price' => $s['price'],
                'description' => 'Jasa ' . $s['name'] . ' oleh mekanik profesional.',
            ]);
        }
    }
}
