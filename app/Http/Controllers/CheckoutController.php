<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = CartItem::where('user_id', Auth::id())->with('itemable', 'serviceProduct')->latest()->get();
        if ($items->isEmpty()) {
            return redirect()->route('products');
        }
        $total = $items->sum(fn($i) => $i->unit_price * $i->quantity);
        return view('checkout.index', compact('items', 'total'));
    }

    public function process(Request $request)
    {
        $items = CartItem::where('user_id', Auth::id())->with('itemable', 'serviceProduct')->get();
        if ($items->isEmpty()) {
            return redirect()->route('products');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'INV-' . now()->format('Ymd') . '-' . str()->upper(str()->random(6)),
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'notes' => $data['notes'],
            'subtotal' => 0,
            'total' => 0,
        ]);

        $subtotal = 0;
        foreach ($items as $ci) {
            $price = $ci->unit_price;
            $qty = $ci->quantity;
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;

            if ($ci->itemable_type === 'App\Models\Product' && $ci->itemable) {
                if ($ci->itemable->stock < $qty) {
                    return redirect()->route('checkout.index')->with('success', 'Stok ' . $ci->itemable->name . ' tidak mencukupi.');
                }
                $ci->itemable->decrement('stock', $qty);
            }

            $order->items()->create([
                'itemable_id' => $ci->itemable_id,
                'itemable_type' => $ci->itemable_type,
                'name' => $ci->name,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $lineTotal,
            ]);
            $ci->delete();
        }

        $order->update(['subtotal' => $subtotal, 'total' => $subtotal]);

        return redirect()->route('orders.pay', $order);
    }
}
