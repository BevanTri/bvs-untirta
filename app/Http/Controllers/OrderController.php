<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\RepairOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function pay(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        if ($order->status === 'cancelled') {
            return redirect()->route('orders.show', $order)->with('error', 'Pesanan telah dibatalkan dan tidak dapat dibayar lagi.');
        }
        return view('orders.pay', compact('order'));
    }

    public function history()
    {
        $productOrders = Order::where('user_id', Auth::id())->latest()->get();
        $repairOrders = RepairOrder::where('user_id', Auth::id())->latest()->get();
        $orders = $productOrders->concat($repairOrders)->sortByDesc('created_at');
        return view('orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()?->is_admin) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()?->is_admin) abort(403);
        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan dengan status Pending yang dapat dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->itemable_type === 'App\Models\Product' && $item->itemable_id) {
                    Product::where('id', $item->itemable_id)->increment('stock', $item->quantity);
                }
            }
            $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);
        });

        return redirect()->route('orders.history')->with('success', 'Pesanan berhasil dibatalkan. Stok produk telah dikembalikan.');
    }

    public function checkPayment(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()?->is_admin) {
            abort(403);
        }
        $payment = $order->payments()->latest()->first();
        if ($payment) {
            $payment->update(['status' => 'berhasil']);
        }
        $order->update(['payment_status' => 'paid', 'status' => 'processing']);
        return back()->with('success', 'Pembayaran dikonfirmasi!');
    }
}
