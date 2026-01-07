<?php
// ================================================
// FILE: routes/web.php
// FUNGSI: Definisi semua route website
// ================================================

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
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\PaymentCallbackController;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ================================================
// HALAMAN PUBLIK (Tanpa Login)
// ================================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::controller(GoogleController::class)->group(function () {
    // ================================================
    // ROUTE 1: REDIRECT KE GOOGLE
    // ================================================
    // URL: /auth/google
    // Dipanggil saat user klik tombol "Login dengan Google"
    // ================================================
    Route::get('/auth/google', 'redirect')
        ->name('auth.google');

    // ================================================
    // ROUTE 2: CALLBACK DARI GOOGLE
    // ================================================
    // URL: /auth/google/callback
    // Dipanggil oleh Google setelah user klik "Allow"
    // URL ini HARUS sama dengan yang didaftarkan di Google Console!
    // ================================================
    Route::get('/auth/google/callback', 'callback')
        ->name('auth.google.callback');
});
// Katalog Produk
Route::get('/products', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])
    ->middleware('auth')
    ->name('wishlist.toggle');

// ================================================
// HALAMAN YANG BUTUH LOGIN (Customer)
// ================================================

Route::middleware('auth')->group(function () {
    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

   // Pesanan Saya
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Payment Routes
    Route::get('/orders/{order}/success', [PaymentController::class, 'success'])
        ->name('orders.success');

    Route::get('/orders/{order}/pending', [PaymentController::class, 'pending'])
        ->name('orders.pending');

    Route::post('/payment/snap/{order}', [PaymentController::class, 'getSnapToken'])
        ->name('payment.snap');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// routes/web.php
// routes/web.php
Route::post('/midtrans/webhook', [MidtransNotificationController::class, 'handle']);


// ============================================================
// MIDTRANS WEBHOOK
// Route ini HARUS public (tanpa auth middleware)
// Karena diakses oleh SERVER Midtrans, bukan browser user
// ============================================================
Route::post('midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification');
Auth::routes();

// Pastikan hanya ada satu blok admin seperti ini
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Memanggil DashboardController, bukan AdminController
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route admin lainnya
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('orders', AdminOrderController::class);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('sales');
        Route::get('/sales/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportSales'])->name('export-sales');
    });
    
    
Route::post('/midtrans/callback', [PaymentCallbackController::class, 'handle']);

});
