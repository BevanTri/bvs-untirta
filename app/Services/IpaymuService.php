<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class IpaymuService
{
    protected string $va;
    protected string $apiKey;
    protected bool $production;
    protected array $urls = [];
    protected array $buyer = [];

    public function __construct()
    {
        $this->va = config('services.ipaymu.va');
        $this->apiKey = config('services.ipaymu.api_key');
        $this->production = !config('services.ipaymu.sandbox', true);
    }

    protected function baseUrl(): string
    {
        return $this->production ? 'https://my.ipaymu.com/api/v2' : 'https://sandbox.ipaymu.com/api/v2';
    }

    protected function signature(string $body): string
    {
        $requestBody = strtolower(hash('sha256', $body));
        $stringToSign = 'POST:' . $this->va . ':' . $requestBody . ':' . $this->apiKey;
        return hash_hmac('sha256', $stringToSign, $this->apiKey);
    }

    protected function request(string $endpoint, array $payload): array
    {
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $signature = $this->signature($body);
        $timestamp = now()->format('YmdHis');

        $ch = curl_init($this->baseUrl() . $endpoint);
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
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('cURL error: ' . $error);
        }

        $decoded = json_decode($response, true) ?? [];
        Log::debug('iPaymu response', ['endpoint' => $endpoint, 'http' => $httpCode, 'response' => $decoded]);

        return $decoded;
    }

    public function setURL(array $urls): void
    {
        $this->urls = $urls;
    }

    public function setBuyer(array $buyer): void
    {
        $this->buyer = $buyer;
    }

    public function checkTransaction(string $referenceId): array
    {
        return $this->request('/payment/detail', [
            'referenceId' => $referenceId,
        ]);
    }

    public function redirectPayment(array $params = []): array
    {
        $payload = [
            'product' => $params['product'] ?? [],
            'qty' => $params['qty'] ?? [],
            'price' => $params['price'] ?? [],
            'returnUrl' => $this->urls['ureturn'] ?? '',
            'cancelUrl' => $this->urls['ucancel'] ?? '',
            'notifyUrl' => $this->urls['unotify'] ?? '',
            'referenceId' => $params['referenceId'] ?? '',
        ];

        return $this->request('/payment', $payload);
    }

    public function createTransaction(Model $payable, string $returnRoute, string $cancelRoute): array
    {
        $products = $payable->items->map(fn($i) => [
            'product' => $i->name ?? $i->product,
            'price' => (string) (int) $i->price,
            'quantity' => (string) ($i->quantity ?? 1),
        ])->toArray();

        if (isset($payable->service_fee) && $payable->service_fee > 0) {
            $products[] = [
                'product' => 'Jasa Service',
                'price' => (string) (int) $payable->service_fee,
                'quantity' => '1',
            ];
        }

        $this->setURL([
            'ureturn' => route($returnRoute, $payable),
            'unotify' => url('/payment/callback'),
            'ucancel' => route($cancelRoute, $payable),
        ]);

        $buyerName = $payable->customer_name ?? $payable->customer?->name ?? 'Customer';
        $buyerEmail = $payable->user?->email ?? 'customer@bengkel.test';

        $this->setBuyer([
            'name' => $buyerName,
            'email' => $buyerEmail,
        ]);

        try {
            $result = $this->redirectPayment([
                'referenceId' => $payable->order_number,
                'product' => array_column($products, 'product'),
                'price' => array_column($products, 'price'),
                'qty' => array_column($products, 'quantity'),
            ]);
        } catch (\Exception $e) {
            Log::error('iPaymu redirectPayment failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }

        $status = $result['Status'] ?? $result['Success'] ?? false;
        if (is_string($status)) {
            $status = $status === 'berhasil' || $status === '1' || $status === 'true';
        }

        if (!$status) {
            Log::error('iPaymu error', ['response' => $result, 'order' => $payable->order_number ?? $payable->id]);
            $msg = $result['Message'] ?? $result['message'] ?? json_encode($result);
            return ['success' => false, 'message' => $msg];
        }

        $data = $result['Data'] ?? [];
        return [
            'success' => true,
            'reference_id' => $data['SessionID'] ?? $payable->order_number,
            'payment_url' => $data['Url'] ?? $data['url'] ?? $data['payment_url'] ?? $data['RedirectUrl'] ?? null,
            'raw' => $result,
        ];
    }

    public function checkPaymentStatus($payment): string
    {
        try {
            $result = $this->checkTransaction($payment->reference_id ?? '');
            $data = $result['Data'] ?? [];
            return $data['Status'] ?? $data['status'] ?? 'pending';
        } catch (\Exception $e) {
            Log::error('iPaymu checkTransaction failed', ['error' => $e->getMessage()]);
            return 'pending';
        }
    }
}
