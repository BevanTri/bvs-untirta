<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/produk', [PageController::class, 'products'])->name('products');
Route::get('/produk/kategori/{category:slug}', [PageController::class, 'products'])->name('products.category');
Route::get('/produk/{product:slug}', [PageController::class, 'productDetail'])->name('product.detail');
Route::get('/service', [PageController::class, 'services'])->name('services');


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
    Route::get('/payment/{order}/success', [PaymentController::class, 'success'])->name('payment.success');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

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

require __DIR__.'/auth.php';
