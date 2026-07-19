<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class IpaymuService
{
    protected string $baseUrl;
    protected string $va;
    protected string $apiKey;
    protected string $callbackUrl;

    public function __construct()
    {
        $this->va = config('services.ipaymu.va');
        $this->apiKey = config('services.ipaymu.api_key');
        $this->callbackUrl = config('services.ipaymu.callback_url')
            ?: url('/payment/callback');
        $this->baseUrl = config('services.ipaymu.sandbox', true)
            ? 'https://sandbox.ipaymu.com/api/v2'
            : 'https://my.ipaymu.com/api/v2';
    }

    public function checkTransaction($payment): string
    {
        $refId = $payment->reference_id ?? '';
        $body = json_encode(['referenceId' => $refId], JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $body));
        $stringToSign = 'POST:' . $this->va . ':' . $requestBody . ':' . $this->apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $this->apiKey);
        $timestamp = date('YmdHis');

        $ch = curl_init($this->baseUrl . '/transaction');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'va: ' . $this->va,
                'signature: ' . $signature,
                'timestamp: ' . $timestamp,
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($raw, true);
        $data = $result['Data'] ?? [];
        return $data['Status'] ?? $data['status'] ?? 'pending';
    }

    public function createTransaction(Order $order): array
    {
        $products = $order->items->map(fn($i) => [
            'name' => $i->name,
            'quantity' => $i->quantity,
            'price' => (int) $i->price,
        ])->toArray();

        $body = [
            'product' => array_column($products, 'name'),
            'qty' => array_map('strval', array_column($products, 'quantity')),
            'price' => array_map('strval', array_column($products, 'price')),
            'returnUrl' => route('payment.success', $order),
            'cancelUrl' => route('orders.pay', $order),
            'notifyUrl' => $this->callbackUrl ?: url('/payment/callback'),
            'referenceId' => $order->order_number,
            'buyerName' => $order->customer_name ?? 'Customer',
            'buyerPhone' => $order->customer_phone ?? '',
            'buyerEmail' => $order->user->email ?? 'customer@bengkel.test',
        ];

        $method = 'POST';
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $this->va . ':' . $requestBody . ':' . $this->apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $this->apiKey);
        $timestamp = date('YmdHis');

        Log::debug('iPaymu request', [
            'url' => $this->baseUrl . '/payment',
            'va' => $this->va,
            'body' => $jsonBody,
            'stringToSign' => $stringToSign,
            'signature' => $signature,
        ]);

        try {
            $ch = curl_init($this->baseUrl . '/payment');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $jsonBody,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'va: ' . $this->va,
                    'signature: ' . $signature,
                    'timestamp: ' . $timestamp,
                ],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);
            $rawResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error('iPaymu curl error', ['error' => $error]);
                return ['success' => false, 'message' => 'Connection error: ' . $error];
            }

            $result = json_decode($rawResponse, true);
            Log::debug('iPaymu response', ['http_code' => $httpCode, 'response' => $result]);
        } catch (\Exception $e) {
            Log::error('iPaymu request failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }

        if ($httpCode !== 200 || !($result['Status'] ?? $result['Success'] ?? false)) {
            Log::error('iPaymu error', ['response' => $result]);
            return ['success' => false, 'message' => $result['Message'] ?? 'HTTP ' . $httpCode];
        }

        $data = $result['Data'] ?? [];
        return [
            'success' => true,
            'reference_id' => $data['ReferenceId'] ?? $data['reference_id'] ?? null,
            'payment_url' => $data['Url'] ?? $data['url'] ?? null,
            'raw' => $result,
        ];
    }
}
