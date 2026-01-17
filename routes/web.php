<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// Google Authentication
Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirect')->name('auth.google');
    Route::get('/auth/google/callback', 'callback')->name('auth.google.callback');
});

// Webhook Midtrans (Wajib di luar middleware auth)
Route::post('/midtrans/notification', [PaymentController::class, 'notificationHandler'])
    ->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| 2. SISTEM AUTENTIKASI (Login, Register, Logout)
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| 3. HALAMAN CUSTOMER (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Keranjang Belanja
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::patch('/{item}', [CartController::class, 'update'])->name('update');
        Route::delete('/{item}', [CartController::class, 'remove'])->name('remove');
    });

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout & Pesanan
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Pembayaran & Redirect Midtrans
    Route::get('/orders/{order}/success', [PaymentController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}/pending', [PaymentController::class, 'pending'])->name('orders.pending');
    Route::post('/payment/snap/{order}', [PaymentController::class, 'getSnapToken'])->name('payment.snap');

    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| 4. HALAMAN ADMIN (Wajib Login Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Produk & Kategori (CRUD)
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    
    // Manajemen Pesanan
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('orders', AdminOrderController::class);

    // Laporan Penjualan
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
        Route::get('/sales/export', [AdminReportController::class, 'exportSales'])->name('export-sales');
    });
});

 Route::get('/generate', function(){
      \Illuminate\Support\Facades\Artisan::call('storage:link');
      echo 'ok';
    });