<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = CartItem::where('user_id', Auth::id())->with('itemable', 'serviceProduct')->latest()->get();
        $total = $items->sum(fn($i) => $i->unit_price * $i->quantity);
        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:product,service',
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'service_product_id' => 'nullable|integer|exists:products,id',
        ]);

        $model = $data['type'] === 'product' ? Product::findOrFail($data['id']) : Service::findOrFail($data['id']);
        $serviceProductId = $request->input('service_product_id');

        $unitPrice = $model->price;
        $name = $model->name;

        if ($serviceProductId) {
            $product = Product::findOrFail($serviceProductId);
            $unitPrice += $product->price;
            $name .= ' + ' . $product->name;
        }

        // Check if same item already in cart → increment qty instead
        $existing = CartItem::where('user_id', Auth::id())
            ->where('itemable_type', $model::class)
            ->where('itemable_id', $model->id)
            ->where('service_product_id', $serviceProductId)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $data['quantity']);
            $itemId = $existing->id;
        } else {
            $item = CartItem::create([
                'user_id' => Auth::id(),
                'itemable_id' => $model->id,
                'itemable_type' => $model::class,
                'service_product_id' => $serviceProductId,
                'quantity' => $data['quantity'],
                'unit_price' => $unitPrice,
                'name' => $name,
            ]);
            $itemId = $item->id;
        }

        if ($request->has('buy_now')) {
            CartItem::where('user_id', Auth::id())->where('id', '!=', $itemId)->delete();
            return redirect()->route('checkout.index')->with('toast', 'Barang ditambahkan ke keranjang');
        }
        return back()->with('toast', 'Barang ditambahkan ke keranjang');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);
        $data = $request->validate(['quantity' => 'required|integer|min:0']);
        if ($data['quantity'] < 1) {
            $cartItem->delete();
        } else {
            $cartItem->update($data);
        }
        return redirect()->route('cart.index');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);
        $cartItem->delete();
        return redirect()->route('cart.index');
    }

    public function checkout(Request $request)
    {
        $selectedIds = $request->input('selected', []);
        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('success', 'Pilih item yang ingin dibeli.');
        }

        $qtyInputs = $request->input('qty', []);
        foreach (CartItem::whereIn('id', $selectedIds)->where('user_id', Auth::id())->get() as $ci) {
            $qty = $qtyInputs[$ci->id] ?? $ci->quantity;
            if ($qty < 1) { $ci->delete(); continue; }
            $ci->update(['quantity' => $qty]);
        }

        $items = CartItem::whereIn('id', $selectedIds)->where('user_id', Auth::id())->with('itemable', 'serviceProduct')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Pilih item yang ingin dibeli.');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'INV-' . now()->format('Ymd') . '-' . str()->upper(str()->random(6)),
            'customer_name' => $data['customer_name'],
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
                    return redirect()->route('cart.index')->with('success', 'Stok ' . $ci->itemable->name . ' tidak mencukupi.');
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
