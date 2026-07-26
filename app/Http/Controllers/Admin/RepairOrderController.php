<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\Order;
use App\Models\Product;
use App\Models\RepairOrder;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepairOrderController extends Controller
{
    public function index(Request $r)
    {
        $q = RepairOrder::with('customer', 'vehicle', 'mechanic');
        if ($s = $r->search) {
            $q->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%$s%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%$s%"));
            });
        }
        if ($status = $r->status) {
            $q->where('status', $status);
        }
        return view('admin.repair-orders', [
            'orders' => $q->latest()->paginate(20),
            'search' => $r->search,
            'filterStatus' => $r->status,
        ]);
    }

    public function create()
    {
        $products = Product::with('category')->where('stock', '>', 0)->orWhereNull('stock')->get();
        $productsJson = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (int) $p->price,
            'price_fmt' => 'Rp' . number_format($p->price, 0, ',', '.'),
            'stock' => $p->stock,
            'image' => $p->image ? asset('uploads/' . $p->image) : null,
            'category' => $p->category->name ?? '',
        ])->values();

        return view('admin.repair-orders-form', [
            'customers' => Customer::all(),
            'vehicles' => Vehicle::all(),
            'mechanics' => Mechanic::all(),
            'productsJson' => $productsJson,
            'categories' => \App\Models\Category::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'date' => 'required|date',
            'complaint' => 'required|string',
            'action' => 'nullable|string',
            'service_fee' => 'required|numeric|min:0',
            'status' => 'required|in:menunggu,proses,selesai,dibatalkan',
            'items' => 'nullable|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $data['order_number'] = 'SRV-' . now()->format('Ymd') . '-' . str()->upper(str()->random(6));
        $data['user_id'] = auth()->id();

        $itemsTotal = 0;
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $itemsTotal += $item['price'] * $item['quantity'];
            }
        }
        $data['total'] = $data['service_fee'] + $itemsTotal;
        $data['date'] = $data['date'] ?? now()->toDateString();

        $order = RepairOrder::create($data);

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                if (!empty($item['product_id'])) {
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                }
            }
        }

        return redirect()->route('admin.repair-orders')->with('success', 'Servis dibuat');
    }

    public function show(RepairOrder $repair_order)
    {
        return view('admin.repair-orders-show', ['order' => $repair_order->load('customer', 'vehicle', 'mechanic', 'items.product', 'payments')]);
    }

    public function edit(RepairOrder $repair_order)
    {
        $products = Product::with('category')->where('stock', '>', 0)->orWhereNull('stock')->get();
        $productsJson = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (int) $p->price,
            'price_fmt' => 'Rp' . number_format($p->price, 0, ',', '.'),
            'stock' => $p->stock,
            'image' => $p->image ? asset('uploads/' . $p->image) : null,
            'category' => $p->category->name ?? '',
        ])->values();

        return view('admin.repair-orders-form', [
            'order' => $repair_order->load('items'),
            'customers' => Customer::all(),
            'vehicles' => Vehicle::all(),
            'mechanics' => Mechanic::all(),
            'productsJson' => $productsJson,
            'categories' => \App\Models\Category::where('is_active', true)->get(),
        ]);
    }

    public function update(Request $r, RepairOrder $repair_order)
    {
        $data = $r->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'date' => 'required|date',
            'complaint' => 'required|string',
            'action' => 'nullable|string',
            'service_fee' => 'required|numeric|min:0',
            'status' => 'required|in:menunggu,proses,selesai,dibatalkan',
            'items' => 'nullable|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        if (array_key_exists('items', $data)) {
            $itemsTotal = 0;
            foreach ($data['items'] as $item) {
                $itemsTotal += $item['price'] * $item['quantity'];
            }
            $data['total'] = $data['service_fee'] + $itemsTotal;
        }

        if ($data['status'] === 'dibatalkan') {
            $data['payment_status'] = 'failed';
        }

        DB::transaction(function () use ($repair_order, $data) {
            $repair_order->update($data);

            if (array_key_exists('items', $data)) {
                $oldItems = $repair_order->items()->get();

                foreach ($oldItems as $old) {
                    if ($old->product_id) {
                        Product::where('id', $old->product_id)->increment('stock', $old->quantity);
                    }
                }

                $repair_order->items()->delete();

                foreach ($data['items'] as $item) {
                    $repair_order->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                    if (!empty($item['product_id'])) {
                        Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                    }
                }
            }

            // convert spareparts → sales transaction
            if (
                in_array($data['status'], ['proses', 'selesai'])
                && $repair_order->payment_status === 'paid'
                && is_null($repair_order->converted_at)
            ) {
                $sparepartItems = $repair_order->items()->whereNotNull('product_id')->get();

                if ($sparepartItems->isNotEmpty()) {
                    $subtotal = $sparepartItems->sum('subtotal');

                    $order = Order::create([
                        'user_id' => $repair_order->user_id ?? auth()->id(),
                        'order_number' => 'INV-' . now()->format('Ymd') . '-' . str()->upper(str()->random(6)),
                        'customer_name' => $repair_order->customer->name,
                        'subtotal' => $subtotal,
                        'total' => $subtotal,
                        'status' => 'completed',
                        'payment_status' => 'paid',
                        'notes' => 'Sparepart dari servis ' . $repair_order->order_number,
                    ]);

                    foreach ($sparepartItems as $spItem) {
                        $order->items()->create([
                            'itemable_id' => $spItem->product_id,
                            'itemable_type' => Product::class,
                            'name' => $spItem->name,
                            'quantity' => $spItem->quantity,
                            'price' => $spItem->price,
                            'subtotal' => $spItem->subtotal,
                        ]);
                    }

                    $repair_order->update(['converted_at' => now()]);
                }
            }
        });

        return redirect()->route('admin.repair-orders')->with('success', 'Servis diupdate');
    }

    public function destroy(RepairOrder $repair_order)
    {
        $repair_order->delete();
        return back()->with('success', 'Servis dihapus');
    }

    public function exportCsv(Request $r)
    {
        $q = RepairOrder::with('customer', 'vehicle', 'mechanic');
        if ($s = $r->search) {
            $q->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%$s%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%$s%"));
            });
        }
        if ($status = $r->status) {
            $q->where('status', $status);
        }
        $orders = $q->latest()->get();
        $headers = ['Content-Type'=>'text/csv; charset=utf-8','Content-Disposition'=>'attachment; filename=repair-orders-'.now()->format('Ymd').'.csv'];
        $callback = function () use ($orders) {
            $fh = fopen('php://output', 'w');
            fprintf($fh, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($fh, ['No. Servis','Pelanggan','Kendaraan','Plat Nomor','Mekanik','Tanggal','Komplain','Tindakan','Biaya Jasa','Item','Total','Status','Pembayaran'], ';');
            foreach ($orders as $o) {
                $items = $o->items->map(fn($i) => $i->name.' x'.$i->quantity.' (@Rp'.number_format($i->price,0,',','.').')')->implode(', ');
                fputcsv($fh, [
                    $o->order_number,
                    $o->customer->name,
                    $o->vehicle->brand.' '.$o->vehicle->model,
                    $o->vehicle->plate_number,
                    $o->mechanic->name ?? '-',
                    $o->date,
                    $o->complaint,
                    $o->action ?? '-',
                    number_format($o->service_fee,0,',','.'),
                    $items,
                    number_format($o->total,0,',','.'),
                    $o->status,
                    $o->payment_status === 'paid' ? 'Lunas' : 'Pending',
                ], ';');
            }
            fclose($fh);
        };
        return response()->stream($callback, 200, $headers);
    }
}
