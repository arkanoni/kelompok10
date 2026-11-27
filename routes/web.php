<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// Tamu: belum login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('register.store');
});

// Pengguna yang sudah login.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Sementara, sesudah login arahkan ke halaman yang sudah tersedia.
    // Dashboard tersendiri disambungkan ketika modul dashboard sudah siap.
    Route::get('/dashboard', function (Request $request) {
        $route = in_array($request->user()->role, ['admin', 'pegawai'], true)
            ? 'reports.inventory'
            : 'products.catalog';

        return redirect()->route($route);
    })->name('dashboard');

    Route::get('/katalog', [ProductController::class, 'catalog'])
        ->name('products.catalog');

    // Menjaga tautan /shop lama tetap menuju katalog, tanpa fitur pemesanan.
    Route::redirect('/shop', '/katalog')->name('shop.index');

    // Admin: tambah, edit, dan hapus kategori.
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)
            ->except(['index', 'show']);
    });

    // Admin dan pegawai: lihat kategori serta kelola produk/stok.
    Route::middleware('role:admin,pegawai')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        // Stok dikelola langsung di tabel produk.
        Route::redirect('/products/stock', '/products');

        Route::resource('products', ProductController::class)
            ->except('show');

        Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])
            ->name('products.stock');
    });
});

// Kedua route laporan didefinisikan di sini, cukup dimuat satu kali.
// File ini sudah membungkus route dengan auth; role diperiksa oleh request laporan.
require __DIR__.'/laporan.php';
