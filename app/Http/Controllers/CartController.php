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
        $items = CartItem::where('user_id', Auth::id())->with('itemable')->latest()->get();
        $total = $items->sum(fn($i) => $i->unit_price * $i->quantity);
        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:product,service',
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $model = $data['type'] === 'product' ? Product::findOrFail($data['id']) : Service::findOrFail($data['id']);

        $unitPrice = $model->price;
        $name = $model->name;

        // Cek apakah item sudah ada di keranjang
        $existing = CartItem::where('user_id', Auth::id())
            ->where('itemable_type', $model::class)
            ->where('itemable_id', $model->id)
            ->first();

        if ($existing) {
            // Update quantity sesuai pilihan user (bukan increment otomatis)
            $existing->update(['quantity' => $data['quantity'], 'unit_price' => $unitPrice, 'name' => $name]);
            $itemId = $existing->id;
        } else {
            $item = CartItem::create([
                'user_id' => Auth::id(),
                'itemable_id' => $model->id,
                'itemable_type' => $model::class,
                'quantity' => $data['quantity'],
                'unit_price' => $unitPrice,
                'name' => $name,
            ]);
            $itemId = $item->id;
        }

        if ($request->has('buy_now')) {
            CartItem::where('user_id', Auth::id())->where('id', '!=', $itemId)->delete();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['redirect' => route('checkout.index')]);
            }
            return redirect()->route('checkout.index');
        }
        return back()->with('toast', 'Barang berhasil ditambahkan ke keranjang.');
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
            return redirect()->route('cart.index')->with('warning', 'Pilih item yang ingin dibeli.');
        }

        $qtyInputs = $request->input('qty', []);
        foreach (CartItem::whereIn('id', $selectedIds)->where('user_id', Auth::id())->get() as $ci) {
            $qty = $qtyInputs[$ci->id] ?? $ci->quantity;
            if ($qty < 1) { $ci->delete(); continue; }
            $ci->update(['quantity' => $qty]);
        }

        return redirect()->route('checkout.index');
    }
}
