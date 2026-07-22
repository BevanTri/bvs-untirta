<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'Toyota', 'Daihatsu', 'Mitsubishi'];
        $models = ['Vario', 'Nmax', 'Aerox', 'Beat', 'Scoopy', 'Supra', 'Jupiter', 'Mio', 'Satria', 'Shogun', 'Avanza', 'Xenia', 'Innova', 'Pajero', 'Rush'];
        $customers = Customer::all();
        foreach ($customers as $c) {
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                Vehicle::create([
                    'customer_id' => $c->id,
                    'plate_number' => strtoupper(fake()->bothify('?? #### ??')),
                    'brand' => $brands[array_rand($brands)],
                    'model' => $models[array_rand($models)],
                ]);
            }
        }
    }
}
