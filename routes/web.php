<?php

use App\Http\Controllers\ProductController;
use Illuminate\Routing\Route;

Route::middleware('auth')->group(function () {

    // Katalog: semua user yang sudah login (admin, pegawai, pembeli)
    Route::get('/katalog', [ProductController::class, 'catalog'])->name('products.catalog');

    // Kelola produk: hanya admin & pegawai
    Route::middleware('role:admin,pegawai')->group(function () {
        Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])
            ->name('products.stock');
        Route::resource('products', ProductController::class)->except('show');
    });
});
