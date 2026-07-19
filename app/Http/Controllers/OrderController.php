<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function pay(Order $order)
    {
        return view('orders.pay', compact('order'));
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()?->is_admin) {
            abort(403);
        }
        return view('orders.show', compact('order'));
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
