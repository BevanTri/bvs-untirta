<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\Product;
use App\Models\RepairOrder;
use App\Models\Service;
use App\Models\Vehicle;
use App\Services\IpaymuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairOrderController extends Controller
{
    public function index()
    {
        $orders = RepairOrder::with('customer', 'vehicle', 'mechanic')
            ->where('user_id', Auth::id())
            ->latest()->paginate(10);
        return view('repairs.index', compact('orders'));
    }

    public function create(Request $r)
    {
        $mechanics = Mechanic::all();
        $services = Service::where('is_active', true)->get();
        $products = Product::with('category')->where(fn($q) => $q->where('stock', '>', 0)->orWhereNull('stock'))->get();
        $categories = \App\Models\Category::where('is_active', true)->get();
        $selectedService = null;
        if ($r->filled('service')) {
            $selectedService = Service::find($r->service);
        }
        $customer = \App\Models\Customer::where('email', Auth::user()->email)->first();
        $vehicles = $customer ? $customer->vehicles : collect();

        $productsJson = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (int) $p->price,
            'price_fmt' => 'Rp' . number_format($p->price, 0, ',', '.'),
            'stock' => $p->stock,
            'image' => $p->image ? asset('uploads/' . $p->image) : null,
            'category' => $p->category->name ?? '',
        ])->values();

        return view('repairs.create', compact('mechanics', 'services', 'productsJson', 'categories', 'selectedService', 'vehicles', 'customer'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'service_id' => 'nullable|exists:services,id',
            'name' => 'required|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'plate_number' => 'required_without:vehicle_id|string',
            'brand' => 'required_without:vehicle_id|string',
            'model' => 'required_without:vehicle_id|string',
            'year' => 'nullable|integer|min:1900|max:2099',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'complaint' => 'required|string',
            'items' => 'nullable|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $customer = Customer::firstOrCreate(
            ['email' => Auth::user()->email],
            ['name' => $data['name']]
        );
        $customer->update(['name' => $data['name']]);

        if (!empty($data['vehicle_id'])) {
            $vehicle = Vehicle::find($data['vehicle_id']);
        } else {
            $vehicle = Vehicle::firstOrCreate(
                ['plate_number' => $data['plate_number']],
                ['customer_id' => $customer->id, 'brand' => $data['brand'], 'model' => $data['model'], 'year' => $data['year'] ?? null]
            );
        }

        $serviceFee = 0;
        if (!empty($data['service_id'])) {
            $service = Service::find($data['service_id']);
            $serviceFee = $service ? $service->price : 0;
        }

        $order = RepairOrder::create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $data['mechanic_id'] ?? null,
            'user_id' => Auth::id(),
            'order_number' => 'SRV-' . now()->format('Ymd') . '-' . str()->upper(str()->random(6)),
            'date' => now()->toDateString(),
            'complaint' => $data['complaint'],
            'service_fee' => $serviceFee,
            'total' => $serviceFee,
            'status' => 'menunggu',
        ]);

        $itemsTotal = 0;
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $itemsTotal += $subtotal;
                $productName = $item['product_id'] ? Product::find($item['product_id'])?->name : 'Sparepart';
                $order->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'name' => $productName,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }
        }
        $order->update(['total' => $serviceFee + $itemsTotal]);

        return redirect()->route('repairs.show', $order)->with('success', 'Servis berhasil diajukan');
    }

    public function show(RepairOrder $repairOrder, Request $r)
    {
        if ($repairOrder->user_id !== Auth::id() && !Auth::user()?->is_admin) {
            abort(403);
        }

        if ($repairOrder->payment_status !== 'paid' && $r->anyFilled(['trx_id', 'sid', 'status'])) {
            $payment = $repairOrder->payments()->latest()->first();
            if ($payment) {
                $payment->update(['status' => 'berhasil', 'raw_response' => $r->all()]);
            }
            $repairOrder->update(['payment_status' => 'paid']);
            session()->flash('success', 'Pembayaran berhasil!');
        }

        return view('repairs.show', ['order' => $repairOrder->load('customer', 'vehicle', 'mechanic', 'items.product', 'payments')]);
    }

    public function pay(RepairOrder $repairOrder)
    {
        if ($repairOrder->user_id !== Auth::id()) abort(403);
        return view('repairs.pay', ['order' => $repairOrder]);
    }

    public function checkPayment(RepairOrder $repairOrder)
    {
        if ($repairOrder->user_id !== Auth::id() && !Auth::user()?->is_admin) abort(403);
        $payment = $repairOrder->payments()->latest()->first();
        if ($payment) {
            $payment->update(['status' => 'berhasil']);
        }
        $repairOrder->update(['payment_status' => 'paid']);
        return back()->with('success', 'Pembayaran dikonfirmasi!');
    }

    public function invoice(RepairOrder $repairOrder)
    {
        if ($repairOrder->user_id !== Auth::id() && !Auth::user()?->is_admin) abort(403);
        return view('repairs.invoice', ['order' => $repairOrder->load('customer', 'vehicle', 'mechanic', 'items')]);
    }
}
