<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoastingController;
use App\Http\Controllers\Admin\BahanBakuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

// --- 1. GUEST ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('remove.from.cart');

// --- 2. PASSWORD RESET ---
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

// --- 3. AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {

    // Cukup gunakan satu ini untuk tambah keranjang (POST)
    Route::post('/add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update-cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Dashboard Multi-Role
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return app(AdminDashboard::class)->index();
        }
        return app(UserDashboard::class)->index();
    })->name('dashboard');

    Route::get('/orders/history', [UserDashboard::class, 'history'])->name('orders.history');

    Route::get('/checkout-success/{id}', function ($id) {
        // Data order berdasarkan ID agar nominal harganya muncul di QRIS
        $order = \App\Models\Order::findOrFail($id);
        return view('checkout_success', compact('order'));
    })->name('checkout.success');

    Route::post('/orders/{id}/rate', [OrderController::class, 'submitRating'])->name('orders.rate');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- 4. KHUSUS ADMIN ---
    Route::middleware(['role:admin'])->group(function () {
        // Produk & Pesanan Admin
        Route::resource('admin/products', ProductController::class)->names('products');
        Route::get('/admin/orders', [ProductController::class, 'orders'])->name('admin.orders');
        Route::get('admin/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::get('admin/sales-history', [OrderController::class, 'salesHistory'])->name('admin.sales.history');

        // Produksi & Stok (Roasting)
        Route::get('admin/roasting', [RoastingController::class, 'index'])->name('admin.roasting');
        Route::post('admin/roasting/process', [RoastingController::class, 'process'])->name('admin.roasting.process');
        Route::post('admin/roasting/packaging', [RoastingController::class, 'processPackaging'])->name('admin.packaging.process');

        // Bahan Baku (Mentah & Matang)
        // Note: Gunakan resource secara hati-hati agar tidak bentrok dengan route manual
        Route::resource('admin/bahan-baku', BahanBakuController::class)->except(['destroy'])->names('bahan_baku');
        Route::delete('admin/bahan-baku/{id}', [BahanBakuController::class, 'destroy'])->name('admin.bahan_baku.destroy');

        Route::post('admin/bahan-baku/restock', [BahanBakuController::class, 'restock'])->name('admin.bahan_baku.restock');
        Route::post('admin/bahan-baku/matang', [BahanBakuController::class, 'storeMatang'])->name('admin.bahan_baku.store_matang');
        Route::delete('admin/bahan-baku-matang/{id}', [BahanBakuController::class, 'destroyMatang'])->name('admin.bahan_baku.destroy_matang');
        Route::put('admin/bahan-baku-matang/{id}', [BahanBakuController::class, 'updateMatang'])->name('admin.bahan_baku.update_matang');

        // Manajemen User
        // Di dalam group middleware admin
        Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');

        Route::get('admin/sales-analysis', [OrderController::class, 'salesAnalysis'])->name('admin.sales.analysis');

        // GANTI KODE LAMA DENGAN INI:
        Route::get('/admin/reviews', [AdminDashboard::class, 'reviews'])->name('admin.reviews.index');

        Route::get('admin/spending-history', [BahanBakuController::class, 'spendingHistory'])->name('admin.spending.history');
    });

    // --- 5. KHUSUS KASIR ---
    Route::middleware(['role:kasir'])->group(function () {
        Route::get('/kasir/orders', [KasirController::class, 'index'])->name('kasir.orders');
        Route::get('/kasir/history', [KasirController::class, 'history'])->name('kasir.history');
        Route::post('/kasir/orders/{id}/status', [KasirController::class, 'updateStatus'])->name('kasir.orders.update');
    });
});

require __DIR__ . '/auth.php';
