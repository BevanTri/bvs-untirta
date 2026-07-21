<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\Product;
use App\Models\RepairOrder;
use App\Models\RepairOrderItem;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class RepairOrderSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['menunggu', 'proses', 'selesai', 'dibatalkan'];
        $complaints = [
            'Mesin brebet saat digas', 'Ban bocor', 'Oli bocor', 'Lampu mati',
            'Aki soak', 'Suara berisik di mesin', 'Rem blong', 'Kopling selip',
            'Rantai kendor', 'Karburator kotor', 'Starter susah', 'Knalpot bocor',
        ];
        $actions = [
            'Bersihkan karburator, setel angin', 'Ganti ban dalam', 'Ganti oli mesin dan filter',
            'Ganti bohlam dan periksa kelistrikan', 'Ganti aki baru', 'Tune up mesin',
            'Ganti kampas rem dan setel', 'Setel kopling', 'Setel dan lumasi rantai',
            'Bersihkan karburator', 'Ganti dinamo starter', 'Las knalpot',
        ];

        $customers = Customer::all();
        $mechanics = Mechanic::all();
        $products = Product::where('stock', '>', 0)->orWhereNull('stock')->get();

        for ($i = 0; $i < 50; $i++) {
            $customer = $customers->random();
            $vehicles = Vehicle::where('customer_id', $customer->id)->get();
            if ($vehicles->isEmpty()) continue;

            $complaint = $complaints[array_rand($complaints)];
            $actionIdx = array_search($complaint, $complaints);
            $action = $actionIdx !== false ? $actions[$actionIdx] : null;
            $date = now()->subDays(rand(0, 90));

            $serviceFee = rand(25000, 150000);
            $itemTotal = 0;

            $order = RepairOrder::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicles->random()->id,
                'mechanic_id' => $mechanics->random()->id,
                'user_id' => null,
                'order_number' => 'SRV-' . $date->format('Ymd') . '-' . strtoupper(fake()->bothify('??????')),
                'date' => $date,
                'complaint' => $complaint,
                'action' => rand(0, 1) ? $action : null,
                'service_fee' => $serviceFee,
                'total' => 0,
                'status' => $statuses[array_rand($statuses)],
                'payment_status' => rand(0, 2) ? 'paid' : 'pending',
            ]);

            if (rand(0, 1) && $products->count()) {
                $usedProducts = $products->random(rand(1, 3));
                foreach ($usedProducts as $p) {
                    $qty = rand(1, 2);
                    $subtotal = $p->price * $qty;
                    $itemTotal += $subtotal;
                    $order->items()->create([
                        'product_id' => $p->id,
                        'name' => $p->name,
                        'quantity' => $qty,
                        'price' => $p->price,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            $order->update(['total' => $serviceFee + $itemTotal]);
        }
    }
}
