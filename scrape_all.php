<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\BrandPartner;
use App\Models\Outlet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

ini_set('memory_limit', '256M');

// ── Get fresh product listing page HTML ──
echo "=== FETCHING PRODUCT PAGES ===" . PHP_EOL;

$categories = ['Ban', 'Aki', 'Shock Absorber', 'Oli'];

foreach ($categories as $catName) {
    echo "\n--- $catName ---\n";

    $cat = Category::firstOrCreate(
        ['name' => $catName],
        ['slug' => str()->slug($catName), 'is_active' => true]
    );

    $resp = Http::get('https://www.shopandbike.co.id/produk?category=' . urlencode($catName));
    if (!$resp->successful()) { echo "  FAILED\n"; continue; }
    $html = $resp->body();

    // Find the main catalog component ID and fingerprints
    preg_match('/"fingerprint"\s*:\s*\{[^}]*"id":"([^"]+)"/', $html, $fp);
    preg_match('/"id":"([^"]+)","name":"client\.product\.catalog"/', $html, $fp2);
    preg_match('/wire:id="([^"]+)"[^>]*wire:initial-data="[^"]*client\.product\.catalog/', $html, $compId);

    $componentId = $compId[1] ?? null;
    $fingerprintId = $fp2[1] ?? $fp[1] ?? null;

    echo "  Component ID: " . ($componentId ?? 'not found') . "\n";
    echo "  Fingerprint ID: " . ($fingerprintId ?? 'not found') . "\n";

    // Try to find the serverMemo to restore state
    preg_match('/"serverMemo":\{"children":\{[^}]*\},"errors":\[\],"htmlHash":"[^"]+","data":\{[^}]*"page":(\d+)/', $html, $pageMatch);
    echo "  Current page from HTML: " . ($pageMatch[1] ?? 'not found') . "\n";

    // Try to call the Livewire message endpoint
    // The Livewire message URL is typically: POST /livewire/message/{component-id}
    if ($componentId) {
        // Try multiple endpoint patterns
        $endpoints = [
            "https://www.shopandbike.co.id/livewire/message/$componentId",
            "https://www.shopandbike.co.id/produk?category=" . urlencode($catName),
        ];

        // Get the livewire_token from the HTML
        preg_match('/window\.livewire_token\s*=\s*\'([^\']+)\'/', $html, $tokenMatch);
        $token = $tokenMatch[1] ?? '';

        foreach ($endpoints as $endpoint) {
            echo "  Trying: $endpoint\n";
            try {
                $resp2 = Http::withHeaders([
                    'X-Livewire' => 'true',
                    'X-CSRF-TOKEN' => $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'text/html, application/json',
                ])->post($endpoint, [
                    'fingerprint' => [
                        'id' => $componentId,
                        'name' => 'client.product.catalog',
                        'locale' => 'en',
                        'path' => 'produk',
                        'method' => 'GET',
                    ],
                    'serverMemo' => [
                        'children' => [],
                        'errors' => [],
                        'data' => [
                            'category' => $catName,
                            'page' => 1,
                            'per_page' => 6,
                        ],
                    ],
                    'updates' => [
                        [
                            'type' => 'callMethod',
                            'payload' => [
                                'method' => 'showMore',
                                'params' => [],
                            ],
                        ],
                    ],
                ]);
                echo "  Status: " . $resp2->status() . "\n";
                if ($resp2->successful()) {
                    $body = $resp2->body();
                    echo "  Response length: " . strlen($body) . "\n";
                    // Look for product data in the response
                    if (strpos($body, 'image-product-wrapper') !== false) {
                        echo "  FOUND PRODUCTS IN RESPONSE!\n";
                        file_put_contents(__DIR__ . '/livewire_response_' . $catName . '.html', $body);
                    }
                    break;
                }
            } catch (\Exception $e) {
                echo "  Error: " . $e->getMessage() . "\n";
            }
        }
    }
}
