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
            $result = $ipaymu->createTransaction($order);
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

        $order->payments()->create([
            'method' => 'ipaymu',
            'amount' => $order->total,
            'reference_id' => $result['reference_id'] ?? $order->order_number,
            'payment_url' => $result['payment_url'],
            'status' => 'pending',
            'raw_response' => $result['raw'],
        ]);

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
        $payment->order->update(['payment_status' => $status === 'berhasil' ? 'paid' : ($status === 'gagal' ? 'failed' : 'pending')]);

        return response('OK', 200);
    }

    public function success(Request $request, Order $order)
    {
        $payment = $order->payments()->latest()->first();
        if ($order->payment_status !== 'paid') {
            $data = $request->all();
            Log::info('Payment success page accessed', ['order_id' => $order->id, 'data' => $data]);

            // Check if iPaymu sent data in query params
            if (isset($data['status']) && $data['status'] === 'berhasil') {
                if ($payment) $payment->update(['status' => 'berhasil', 'raw_response' => $data]);
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            } elseif (isset($data['trx_id'])) {
                // iPaymu sends trx_id when redirecting back
                if ($payment) $payment->update(['status' => 'berhasil', 'raw_response' => $data]);
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            }

            // Dump all request data for debugging
            file_put_contents(storage_path('logs/ipaymu_return.log'),
                date('Y-m-d H:i:s') . ' | Order: ' . $order->id . ' | URL: ' . $request->fullUrl() . "\n"
                . 'GET: ' . json_encode($request->query->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
                . 'POST: ' . json_encode($request->request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
                . 'Headers: ' . json_encode($request->headers->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n",
                FILE_APPEND);
        }
        return view('orders.success', compact('order'));
    }
}
