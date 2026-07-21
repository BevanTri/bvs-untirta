<?php

namespace App\Http\Controllers;

use App\Models\BrandPartner;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\Order;
use App\Models\Product;
use App\Models\RepairOrder;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $hari = ['Sun' => 'Min', 'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab'];
        $chartData = collect(range(0, 6))->map(function ($i) use ($hari) {
            $date = now()->startOfWeek()->addDays($i);
            $total = RepairOrder::whereDate('created_at', $date)->sum('total') + Order::whereDate('created_at', $date)->sum('total');
            $count = RepairOrder::whereDate('created_at', $date)->count() + Order::whereDate('created_at', $date)->count();
            return ['label' => $hari[$date->format('D')] ?? $date->format('D'), 'total' => $total, 'count' => $count];
        });
        $chartMax = $chartData->max('total') ?: 1;

        return view('admin.dashboard', [
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'processingOrders' => Order::where('status', 'processing')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'cancelledOrders' => Order::where('status', 'cancelled')->count(),
            'paidOrders' => Order::where('payment_status', 'paid')->count(),
            'totalRevenue' => Order::sum('total') + RepairOrder::sum('total'),
            'recentOrders' => Order::with('user', 'items')->latest()->take(10)->get(),
            'totalCustomers' => Customer::count(),
            'totalVehicles' => Vehicle::count(),
            'totalRepairOrders' => RepairOrder::count(),
            'todayRepairs' => RepairOrder::whereDate('date', today())->count(),
            'chartData' => $chartData,
            'chartMax' => $chartMax,
        ]);
    }

    public function categories()
    {
        return view('admin.categories', ['categories' => Category::withCount('products')->latest()->get()]);
    }

    public function products(Request $r)
    {
        $query = Product::with('category');
        if ($s = $r->search) $query->where('name', 'like', "%$s%");
        if ($c = $r->category_id) $query->where('category_id', $c);
        return view('admin.products', [
            'products' => $query->get(),
            'categories' => Category::all(),
            'search' => $r->search,
            'filterCat' => $r->category_id,
        ]);
    }

    public function services()
    {
        return view('admin.services', ['services' => Service::latest()->get()]);
    }

    public function brands()
    {
        return view('admin.brands', ['brands' => BrandPartner::latest()->get()]);
    }

    public function orders(Request $r)
    {
        $q = Order::with('items', 'user');
        if ($s = $r->search) {
            $q->where(function($query) use ($s) {
                $query->where('order_number', 'like', "%$s%")
                    ->orWhere('customer_name', 'like', "%$s%")
                    ->orWhere('customer_phone', 'like', "%$s%");
            });
        }
        return view('admin.orders', ['orders' => $q->latest()->paginate(20), 'search' => $r->search]);
    }

    public function exportOrdersCsv()
    {
        $orders = Order::with('items', 'user')->latest()->get();
        $headers = ['Content-Type'=>'text/csv; charset=utf-8','Content-Disposition'=>'attachment; filename=orders-'.now()->format('Ymd').'.csv'];
        $callback = function () use ($orders) {
            $fh = fopen('php://output', 'w');
            fprintf($fh, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($fh, ['ID','Customer','Email','Subtotal','Ongkir','Total','Status','Tanggal','Items'], ';');
            foreach ($orders as $o) {
                $items = $o->items->map(fn($i) => $i->name.' x'.$i->quantity.' (@Rp'.number_format($i->price,0,',','.').')')->implode(', ');
                fputcsv($fh, [$o->id,$o->user->name,$o->user->email,$o->subtotal,$o->shipping_cost ?? 0,$o->total,$o->status,$o->created_at,$items], ';');
            }
            fclose($fh);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function invoice(Order $order)
    {
        return view('admin.invoice', ['order' => $order->load('items')]);
    }

    public function storeCategory(Request $r) { Category::create([...$r->validate(['name'=>'required']), 'slug' => str($r->name)->slug()->toString()]); return back(); }
    public function storeProduct(Request $r) {
        $data = $r->validate(['category_id'=>'required|exists:categories,id','name'=>'required','price'=>'required|numeric','description'=>'nullable|string','stock'=>'nullable|integer','image'=>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048']);
        $data['slug'] = str($r->name)->slug()->toString();
        if ($r->hasFile('image')) $data['image'] = $r->file('image')->store('products', 'public');
        Product::create($data);
        return back();
    }
    public function storeService(Request $r) { Service::create([...$r->validate(['name'=>'required','price'=>'required|numeric']), 'slug' => str($r->name)->slug()->toString()]); return back(); }
    public function storeBrand(Request $r) {
        $data = $r->validate(['name'=>'required','logo'=>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048']);
        if ($r->hasFile('logo')) $data['logo'] = $r->file('logo')->store('brands', 'public');
        BrandPartner::create($data);
        return back();
    }

    public function updateCategory(Request $r, Category $category) { $category->update([...$r->validate(['name'=>'required']), 'slug' => str($r->name)->slug()->toString()]); return back(); }
    public function updateProduct(Request $r, Product $product) {
        $data = $r->validate(['category_id'=>'required|exists:categories,id','name'=>'required','price'=>'required|numeric','description'=>'nullable|string','stock'=>'nullable|integer','image'=>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048']);
        if ($r->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $r->file('image')->store('products', 'public');
        }
        $product->update($data);
        return back()->with('success', 'Produk berhasil diupdate');
    }
    public function updateService(Request $r, Service $service) { $service->update($r->validate(['name'=>'required','price'=>'required|numeric'])); return back(); }
    public function updateBrand(Request $r, BrandPartner $brand) {
        $data = $r->validate(['name'=>'required','logo'=>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048']);
        if ($r->hasFile('logo')) {
            if ($brand->logo) Storage::disk('public')->delete($brand->logo);
            $data['logo'] = $r->file('logo')->store('brands', 'public');
        }
        $brand->update($data);
        return back();
    }
    public function updateOrder(Order $order, Request $r) { $order->update($r->validate(['status'=>'required|in:pending,processing,completed,cancelled','payment_status'=>'nullable|in:pending,paid,failed'])); return back()->with('success', 'Pesanan diupdate'); }

    public function destroyCategory(Category $category) { $category->delete(); return back(); }
    public function destroyProduct(Product $product) { $product->delete(); return back(); }
    public function destroyService(Service $service) { $service->delete(); return back(); }
    public function destroyBrand(BrandPartner $brand) { $brand->delete(); return back(); }

    public function scrapeImages()
    {
        $categories = ['Ban' => 5, 'Oli' => 2, 'Aki' => 4, 'Shock%20Absorber' => 3];
        $allProducts = [];
        $totalDownloaded = 0;

        foreach ($categories as $catName => $catId) {
            $page = 1;
            $maxPages = 50;
            while ($page <= $maxPages) {
                $url = "https://www.shopandbike.co.id/produk?category=$catName&page=$page";
                $html = @file_get_contents($url);
                if (!$html) break;

                $dom = new \DOMDocument();
                @$dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);

                $products = [];
                $nodes = $xpath->query("//div[contains(@class,'product-list')]//a[contains(@href,'/produk/')]");
                if ($nodes->length === 0) {
                    $nodes = $xpath->query("//a[contains(@href,'/produk/')]");
                }

                $found = false;
                foreach ($nodes as $node) {
                    $href = $node->getAttribute('href');
                    if (preg_match('#/produk/(.+)#', $href, $m)) {
                        $slug = $m[1];
                        $name = trim($node->getAttribute('title')) ?: trim($node->textContent);
                        if (!$name) {
                            $nameNode = $xpath->query(".//*[contains(@class,'name')]", $node)->item(0);
                            if ($nameNode) $name = trim($nameNode->textContent);
                        }

                        $imgNode = $xpath->query(".//img", $node)->item(0);
                        $imgSrc = '';
                        if ($imgNode) {
                            $imgSrc = $imgNode->getAttribute('src') ?: $imgNode->getAttribute('data-src') ?: '';
                            if ($imgSrc && !str_starts_with($imgSrc, 'http')) {
                                $imgSrc = 'https://www.shopandbike.co.id' . $imgSrc;
                            }
                        }

                        if ($slug && $name) {
                            $allProducts[] = ['name' => $name, 'slug' => $slug, 'image' => $imgSrc, 'category_id' => $catId];
                            $found = true;
                        }
                    }
                }

                if (!$found) break;
                $page++;
                sleep(0.5);
            }
        }

        // Now try individual product pages for better images
        $seen = [];
        $count = 0;
        foreach ($allProducts as $item) {
            $key = strtolower(trim($item['name']));
            if (isset($seen[$key])) continue;
            $seen[$key] = true;

            $local = Product::where('name', $item['name'])->first();
            if (!$local) {
                $local = Product::whereRaw('LOWER(TRIM(name)) = ?', [$key])->first();
            }
            if (!$local || $local->image) continue;

            if ($item['image']) {
                $ext = pathinfo(parse_url($item['image'], PHP_URL_PATH), PATHINFO_EXTENSION);
                $ext = in_array($ext, ['jpg','jpeg','png','webp']) ? $ext : 'jpg';
                $fname = 'products/scrape_' . $local->id . '.' . $ext;
                $fpath = storage_path('app/public/' . $fname);
                $dir = dirname($fpath);
                if (!is_dir($dir)) mkdir($dir, 0755, true);

                $imgData = @file_get_contents($item['image']);
                if ($imgData) {
                    file_put_contents($fpath, $imgData);
                    $local->update(['image' => $fname]);
                    $count++;
                    continue;
                }
            }

            // Fallback: try product detail page
            $detailUrl = 'https://www.shopandbike.co.id/produk/' . $item['slug'];
            $html = @file_get_contents($detailUrl);
            if ($html) {
                $dom = new \DOMDocument();
                @$dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);
                $imgNode = $xpath->query("//div[contains(@class,'product-detail')]//img")->item(0)
                    ?? $xpath->query("//div[contains(@class,'product-image')]//img")->item(0)
                    ?? $xpath->query("//main//img[contains(@src,'product')]")->item(0);

                if ($imgNode) {
                    $src = $imgNode->getAttribute('src') ?: $imgNode->getAttribute('data-src') ?: '';
                    if ($src && !str_starts_with($src, 'http')) {
                        $src = 'https://www.shopandbike.co.id' . $src;
                    }
                    if ($src) {
                        $ext = pathinfo(parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $ext = in_array($ext, ['jpg','jpeg','png','webp']) ? $ext : 'jpg';
                        $fname = 'products/scrape_' . $local->id . '.' . $ext;
                        $fpath = storage_path('app/public/' . $fname);
                        $imgData = @file_get_contents($src);
                        if ($imgData) {
                            file_put_contents($fpath, $imgData);
                            $local->update(['image' => $fname]);
                            $count++;
                        }
                    }
                }
            }
        }

        return response("Selesai! Download $count gambar dari " . count($allProducts) . " produk ditemukan.", 200)->header('Content-Type', 'text/plain');
    }

    public function syncImages()
    {
        $count = 0;
        foreach (Product::all() as $p) {
            foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
                $path = storage_path("app/public/products/{$p->id}.{$ext}");
                if (file_exists($path)) {
                    $p->update(['image' => "products/{$p->id}.{$ext}"]);
                    $count++;
                    break;
                }
            }
        }
        return response("Sync $count images sukses!", 200);
    }

    public function generatePlaceholders()
    {
        if (!function_exists('imagecreatetruecolor')) {
            return response('GD library tidak tersedia', 500);
        }

        $products = Product::whereNull('image')->orWhere('image', '')->get();
        $count = 0;
        $pal = [
            ['#1E3A5F','#2563EB','#3B82F6'],
            ['#92400E','#F59E0B','#FBBF24'],
            ['#065F46','#10B981','#34D399'],
            ['#991B1B','#EF4444','#F87171'],
            ['#5B21B6','#8B5CF6','#A78BFA'],
            ['#9D174D','#EC4899','#F472B6'],
            ['#0F766E','#14B8A6','#2DD4BF'],
            ['#9A3412','#F97316','#FB923C'],
        ];

        foreach ($products as $p) {
            [$c1,$c2,$c3] = $pal[$p->category_id % count($pal)];
            $s = 400;
            $im = imagecreatetruecolor($s, $s);
            [$r1,$g1,$b1] = sscanf($c1, '#%02x%02x%02x');
            $bg = imagecolorallocate($im, $r1, $g1, $b1);
            imagefill($im, 0, 0, $bg);
            [$r2,$g2,$b2] = sscanf($c2, '#%02x%02x%02x');
            $fg = imagecolorallocate($im, $r2, $g2, $b2);
            imagefilledellipse($im, $s/2, $s/2, 350, 350, $fg);
            [$r3,$g3,$b3] = sscanf($c3, '#%02x%02x%02x');
            $hl = imagecolorallocate($im, $r3, $g3, $b3);
            imagefilledellipse($im, $s/3, $s/3, 180, 180, $hl);
            $w = imagecolorallocate($im, 255, 255, 255);
            $inits = mb_substr($p->name, 0, 1, 'UTF-8');
            $x = ($s - imagefontwidth(5) * 2) / 2;
            $y = ($s - imagefontheight(5)) / 2;
            imagestring($im, 5, (int)$x, (int)$y, $inits ?? 'P', $w);
            $name = 'products/p_' . $p->id . '.png';
            $fp = storage_path('app/public/'.$name);
            $d = dirname($fp);
            if (!is_dir($d)) mkdir($d, 0755, true);
            imagepng($im, $fp);
            imagedestroy($im);
            $p->update(['image' => $name]);
            $count++;
        }

        return response("Generate $count placeholder images sukses!", 200)->header('Content-Type', 'text/plain');
    }

    public function runArtisan(Request $r)
    {
        if ($r->isMethod('get')) {
            return view('admin.artisan');
        }

        $cmd = $r->input('cmd', 'migrate');
        $allowed = ['migrate', 'migrate:fresh', 'db:seed', 'db:seed --class=DatabaseSeeder', 'db:seed --class=CustomerSeeder', 'db:seed --class=VehicleSeeder', 'db:seed --class=MechanicSeeder', 'db:seed --class=RepairOrderSeeder', 'db:seed --class=CategorySeeder', 'db:seed --class=ServiceSeeder', 'db:seed --class=BrandPartnerSeeder', 'db:seed --class=ProductSeeder'];
        if (!in_array($cmd, $allowed)) {
            return response('Command not allowed', 403);
        }
        try {
            $exitCode = \Illuminate\Support\Facades\Artisan::call($cmd, ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            return response("Exit: $exitCode\n\n$output", 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response("Error: " . $e->getMessage(), 500)->header('Content-Type', 'text/plain');
        }
    }
}
