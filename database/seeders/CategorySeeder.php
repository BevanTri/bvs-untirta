<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Ban', 'Aki', 'Shock Absorber', 'Oli'];
        foreach ($categories as $name) {
            Category::create(['name' => $name, 'slug' => str()->slug($name)]);
        }
    }
}
