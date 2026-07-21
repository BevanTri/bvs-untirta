<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\IpaymuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function checkout(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->user) {
            Log::error('Payment checkout failed: order user not found', ['order_id' => $order->id]);
            return back()->with('error', 'Data pengguna tidak ditemukan.');
        }

        try {
            $ipaymu = new IpaymuService();
            $result = $ipaymu->createTransaction($order, 'payment.success', 'orders.pay');
        } catch (\Exception $e) {
            Log::error('Payment checkout exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Gagal menghubungi gateway pembayaran: ' . $e->getMessage());
        }

        if (!$result['success']) {
            Log::warning('iPaymu transaction failed', [
                'order_id' => $order->id,
                'message' => $result['message'],
            ]);
            return back()->with('error', 'Gagal memproses pembayaran: ' . $result['message']);
        }

        $payment = $order->payments()->create([
            'method' => 'ipaymu',
            'amount' => $order->total,
            'reference_id' => $result['reference_id'] ?? $order->order_number,
            'payment_url' => $result['payment_url'],
            'status' => 'pending',
            'raw_response' => $result['raw'],
        ]);
        $payment->update(['payable_type' => Order::class, 'payable_id' => $order->id]);

        $order->update(['payment_status' => 'pending']);

        Log::info('Payment redirecting to iPaymu', [
            'order_id' => $order->id,
            'payment_url' => $result['payment_url'],
        ]);

        return redirect($result['payment_url']);
    }

    public function callback(Request $request)
    {
        $data = $request->input();
        if (empty($data)) {
            $data = $request->json()->all();
        }
        Log::info('iPaymu callback received', ['body' => $data]);

        $referenceId = $data['reference_id'] ?? null;
        if (!$referenceId) {
            return response('OK', 200);
        }

        $payment = Payment::where('reference_id', $referenceId)->latest()->first();
        if (!$payment) {
            return response('OK', 200);
        }

        $status = $data['status'] ?? 'pending';
        $payment->update(['status' => $status, 'raw_response' => $data]);

        $payable = $payment->payable;
        if ($payable) {
            $payable->update(['payment_status' => $status === 'berhasil' ? 'paid' : ($status === 'gagal' ? 'failed' : 'pending')]);
            if ($status === 'berhasil' && $payable instanceof Order) {
                $payable->update(['status' => 'processing']);
            }
        }

        return response('OK', 200);
    }

    public function success(Request $request, Order $order)
    {
        $payment = $order->payments()->latest()->first();
        if ($order->payment_status !== 'paid') {
            $data = $request->all();
            Log::info('Payment success page accessed', ['order_id' => $order->id, 'data' => $data]);

            if (isset($data['status']) && $data['status'] === 'berhasil') {
                if ($payment) $payment->update(['status' => 'berhasil', 'raw_response' => $data]);
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            } elseif (isset($data['trx_id'])) {
                if ($payment) $payment->update(['status' => 'berhasil', 'raw_response' => $data]);
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            }

            file_put_contents(storage_path('logs/ipaymu_return.log'),
                date('Y-m-d H:i:s') . ' | Order: ' . $order->id . ' | URL: ' . $request->fullUrl() . "\n"
                . 'GET: ' . json_encode($request->query->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
                . 'POST: ' . json_encode($request->request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
                . 'Headers: ' . json_encode($request->headers->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n",
                FILE_APPEND);
        }
        return view('orders.success', compact('order'));
    }

    public function payRepair(\App\Models\RepairOrder $repairOrder)
    {
        if ($repairOrder->user_id !== Auth::id()) abort(403);

        try {
            $ipaymu = new IpaymuService();
            $result = $ipaymu->createTransaction($repairOrder, 'repairs.show', 'repairs.pay');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi gateway pembayaran: ' . $e->getMessage());
        }

        if (!$result['success']) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $result['message']);
        }

        $payment = $repairOrder->payments()->create([
            'method' => 'ipaymu',
            'amount' => $repairOrder->total,
            'reference_id' => $result['reference_id'] ?? $repairOrder->order_number,
            'payment_url' => $result['payment_url'],
            'status' => 'pending',
            'raw_response' => $result['raw'],
        ]);
        $payment->update(['payable_type' => \App\Models\RepairOrder::class, 'payable_id' => $repairOrder->id]);

        $repairOrder->update(['payment_status' => 'pending']);

        return redirect($result['payment_url']);
    }
}
