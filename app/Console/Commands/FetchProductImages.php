<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FetchProductImages extends Command
{
    protected $signature = 'products:fetch-images';
    protected $description = 'Download product images from si-tepat.com via API';

    public function handle()
    {
        ini_set('memory_limit', '256M');

        $this->info('Fetching all products from si-tepat.com...');
        $remoteProducts = collect();
        $page = 1;

        do {
            $response = Http::get('https://si-tepat.com/wp-json/wp/v2/product', [
                'per_page' => 100, 'page' => $page, '_fields' => 'title,yoast_head_json,slug'
            ]);
            if (!$response->successful()) break;
            $data = collect($response->json());
            $remoteProducts = $remoteProducts->concat($data);
            $page++;
        } while ($data->count() === 100);

        $this->info('Found ' . $remoteProducts->count() . ' remote products');

        $indexed = $remoteProducts->mapWithKeys(function ($r) {
            $key = strtolower(trim(preg_replace('/[^a-z0-9 ]/', '', $r['title']['rendered'])));
            return [$key => $r];
        });
        unset($remoteProducts);

        $downloaded = 0;
        $local = Product::whereNull('image')->orWhere('image', '')->get();
        if ($local->isEmpty()) $local = Product::all();

        foreach ($local as $product) {
            if ($product->image && !str_starts_with($product->image, 'products/')) continue;

            $needle = strtolower(trim(preg_replace('/[^a-z0-9 ]/', '', $product->name)));
            $needleWords = explode(' ', $needle);

            $match = null;
            $bestScore = 0;

            if (isset($indexed[$needle])) {
                $match = $indexed[$needle];
            } else {
                $candidates = collect();
                foreach ($indexed as $key => $r) {
                    $shared = 0;
                    foreach ($needleWords as $w) {
                        if (strlen($w) > 2 && str_contains($key, $w)) $shared++;
                    }
                    if ($shared >= min(3, count($needleWords))) {
                        $candidates->push(['key' => $key, 'item' => $r]);
                    }
                }
                foreach ($candidates as $c) {
                    $len = max(strlen($needle), strlen($c['key']));
                    $dist = levenshtein($needle, $c['key']);
                    $pct = $len > 0 ? (1 - $dist / $len) * 100 : 0;
                    if ($pct > $bestScore) {
                        $bestScore = $pct;
                        $match = $c['item'];
                    }
                }
                unset($candidates);
                if ($bestScore < 70) {
                    $this->warn("No match ({$bestScore}%): {$product->name}");
                    continue;
                }
            }

            $imageUrl = $match['yoast_head_json']['og_image'][0]['url'] ?? null;
            if (!$imageUrl) { $this->warn("No image URL: {$product->name}"); continue; }

            $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'products/' . $product->id . '.' . $ext;

            try {
                $img = Http::timeout(10)->get($imageUrl);
                if ($img->successful()) {
                    Storage::disk('public')->put($filename, $img->body());
                    $product->update(['image' => $filename]);
                    $downloaded++;
                    $this->info("OK: {$product->name}");
                }
            } catch (\Exception $e) {
                $this->warn("Failed: {$product->name}");
            }

            if ($downloaded % 20 === 0) gc_collect_cycles();
        }

        $this->info("Done. $downloaded images downloaded.");
        return 0;
    }
}
