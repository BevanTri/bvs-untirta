<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\MechanicController as AdminMechanicController;
use App\Http\Controllers\Admin\RepairOrderController as AdminRepairOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/produk', [PageController::class, 'products'])->name('products');
Route::get('/produk/kategori/{category:slug}', [PageController::class, 'products'])->name('products.category');
Route::get('/produk/{product:slug}', [PageController::class, 'productDetail'])->name('product.detail');
Route::get('/service', [PageController::class, 'services'])->name('services');

Route::middleware(['auth'])->group(function () {
    Route::get('/servis', [RepairOrderController::class, 'index'])->name('repairs.index');
    Route::get('/servis/buat', [RepairOrderController::class, 'create'])->name('repairs.create');
    Route::post('/servis', [RepairOrderController::class, 'store'])->name('repairs.store');
    Route::get('/servis/{repairOrder}', [RepairOrderController::class, 'show'])->name('repairs.show');
    Route::get('/servis/{repairOrder}/bayar', [RepairOrderController::class, 'pay'])->name('repairs.pay');
    Route::post('/servis/{repairOrder}/check-payment', [RepairOrderController::class, 'checkPayment'])->name('repairs.check-payment');
    Route::get('/servis/{repairOrder}/invoice', [RepairOrderController::class, 'invoice'])->name('repairs.invoice');
});


Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/pesanan', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{order}/bayar', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/pesanan/{order}/check-payment', [OrderController::class, 'checkPayment'])->name('orders.check-payment');
    Route::get('/pesanan/{order}/invoice', [AdminController::class, 'invoice'])->name('orders.invoice');

    Route::post('/payment/checkout/{order}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/repair/{repairOrder}', [PaymentController::class, 'payRepair'])->name('payment.repair');
    Route::get('/payment/{order}/success', [PaymentController::class, 'success'])->name('payment.success');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::patch('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
    Route::patch('/services/{service}', [AdminController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [AdminController::class, 'destroyService'])->name('services.destroy');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
    Route::post('/customers', [AdminCustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/{customer}', [AdminCustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles');
    Route::post('/vehicles', [AdminVehicleController::class, 'store'])->name('vehicles.store');
    Route::post('/vehicles/{vehicle}', [AdminVehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');

    Route::get('/mechanics', [AdminMechanicController::class, 'index'])->name('mechanics');
    Route::post('/mechanics', [AdminMechanicController::class, 'store'])->name('mechanics.store');
    Route::patch('/mechanics/{mechanic}', [AdminMechanicController::class, 'update'])->name('mechanics.update');
    Route::delete('/mechanics/{mechanic}', [AdminMechanicController::class, 'destroy'])->name('mechanics.destroy');

    Route::get('/repair-orders', [AdminRepairOrderController::class, 'index'])->name('repair-orders');
    Route::get('/repair-orders/create', [AdminRepairOrderController::class, 'create'])->name('repair-orders.create');
    Route::post('/repair-orders', [AdminRepairOrderController::class, 'store'])->name('repair-orders.store');
    Route::get('/repair-orders/{repair_order}', [AdminRepairOrderController::class, 'show'])->name('repair-orders.show');
    Route::get('/repair-orders/{repair_order}/edit', [AdminRepairOrderController::class, 'edit'])->name('repair-orders.edit');
    Route::post('/repair-orders/{repair_order}', [AdminRepairOrderController::class, 'update'])->name('repair-orders.update');
    Route::delete('/repair-orders/{repair_order}', [AdminRepairOrderController::class, 'destroy'])->name('repair-orders.destroy');

    Route::get('/brands', [AdminController::class, 'brands'])->name('brands');
    Route::post('/brands', [AdminController::class, 'storeBrand'])->name('brands.store');
    Route::patch('/brands/{brand}', [AdminController::class, 'updateBrand'])->name('brands.update');
    Route::delete('/brands/{brand}', [AdminController::class, 'destroyBrand'])->name('brands.destroy');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/export', [AdminController::class, 'exportOrdersCsv'])->name('orders.export');
    Route::post('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
    Route::get('/orders/{order}/invoice', [AdminController::class, 'invoice'])->name('orders.invoice');

    Route::match(['get', 'post'], '/artisan', [AdminController::class, 'runArtisan'])->name('artisan');
    Route::get('/generate-placeholders', [AdminController::class, 'generatePlaceholders'])->name('generate.placeholders');
    Route::get('/sync-images', [AdminController::class, 'syncImages'])->name('sync.images');
});

Route::get('/uploads/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath, ['Cache-Control' => 'public, max-age=86400']);
})->where('path', '.*')->name('storage.uploads');

Route::get('/storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath, ['Cache-Control' => 'public, max-age=86400']);
})->where('path', '.*');

Route::withoutMiddleware(['auth', 'admin'])->get('/setup', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
    return nl2br(\Illuminate\Support\Facades\Artisan::output());
});

Route::withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])->get('/fix-session', function () {
    $output = '';
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output .= \Illuminate\Support\Facades\Artisan::output() . "\n";
    } catch (\Exception $e) {
        $output .= $e->getMessage() . "\n";
    }
    try {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE payments MODIFY order_id BIGINT UNSIGNED NULL');
        $output .= "payments.order_id set to nullable.\n";
    } catch (\Exception $e) {
        $output .= "ALTER TABLE payments: " . $e->getMessage() . "\n";
    }
    return nl2br($output);
});

Route::withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])->get('/sync-products-images', function () {
    $count = 0;
    foreach (\App\Models\Product::all() as $p) {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
            $path = public_path("uploads/products/{$p->id}.{$ext}");
            if (file_exists($path)) {
                $p->update(['image' => "products/{$p->id}.{$ext}"]);
                $count++;
                break;
            }
        }
    }
    return "Sync $count images sukses!";
});

Route::withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])->get('/reseed', function () {
    try { \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]); } catch (\Exception $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'ProductSeeder', '--force' => true]); } catch (\Exception $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'CustomerSeeder', '--force' => true]); } catch (\Exception $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'VehicleSeeder', '--force' => true]); } catch (\Exception $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'MechanicSeeder', '--force' => true]); } catch (\Exception $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'RepairOrderSeeder', '--force' => true]); } catch (\Exception $e) {}
    return 'Seed selesai (duplikat dilewati)';
});

Route::middleware(['auth', 'admin'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reset-workshop', function () {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \App\Models\Payment::where('payable_type', 'App\\Models\\RepairOrder')->delete();
        \App\Models\RepairOrderItem::truncate();
        \App\Models\RepairOrder::truncate();
        \App\Models\Vehicle::truncate();
        \App\Models\Customer::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
        return 'Semua data workshop (customers, vehicles, repair orders, payments) berhasil dihapus!';
    })->name('reset-workshop');

    Route::get('/seed/{name}', function (string $name) {
        $allowed = ['CustomerSeeder', 'VehicleSeeder', 'MechanicSeeder', 'RepairOrderSeeder', 'CategorySeeder', 'ServiceSeeder', 'BrandPartnerSeeder', 'ProductSeeder', 'DatabaseSeeder'];
        if (!in_array($name, $allowed)) {
            return 'Seeder not allowed';
        }
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => $name, '--force' => true]);
        return nl2br(\Illuminate\Support\Facades\Artisan::output());
    })->name('seed');
});

Route::get('/debug-ipaymu', function () {
    $va = config('services.ipaymu.va');
    $apiKey = config('services.ipaymu.api_key');
    $payload = [
        'product' => ['Test Product'],
        'qty' => ['1'],
        'price' => ['1000'],
        'returnUrl' => url('/'),
        'cancelUrl' => url('/'),
        'notifyUrl' => url('/payment/callback'),
        'referenceId' => 'test-debug-' . time(),
    ];
    $body = json_encode($payload, JSON_UNESCAPED_SLASHES);
    $requestBody = strtolower(hash('sha256', $body));
    $stringToSign = 'POST:' . $va . ':' . $requestBody . ':' . $apiKey;
    $signature = hash_hmac('sha256', $stringToSign, $apiKey);
    $timestamp = date('YmdHis');
    $ch = curl_init('https://sandbox.ipaymu.com/api/v2/payment');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
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
    return response()->json([
        'http_code' => $httpCode,
        'curl_error' => $error,
        'response' => json_decode($response, true),
        'payload_sent' => $payload,
        'signature' => $signature,
        'va' => $va,
    ]);
});

require __DIR__.'/auth.php';
