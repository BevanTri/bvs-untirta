<?php

namespace Database\Seeders;

use App\Models\BrandPartner;
use Illuminate\Database\Seeder;

class BrandPartnerSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['Pertamina Lubricant', 'brands/1.png'],
            ['Shell', 'brands/2.png'],
            ['Aspira Premio', 'brands/3.png'],
            ['Castrol', 'brands/4.png'],
            ['Gs Astra', 'brands/5.png'],
            ['Kayaba', 'brands/6.png'],
            ['Maxio', 'brands/7.png'],
            ['Motul', 'brands/8.png'],
            ['Pirelli', 'brands/9.png'],
            ['Repsol', 'brands/10.png'],
            ['Yamalube', 'brands/11.png'],
        ];

        foreach ($brands as $b) {
            BrandPartner::create([
                'name' => $b[0],
                'logo' => $b[1],
                'is_active' => true,
            ]);
        }
    }
}
