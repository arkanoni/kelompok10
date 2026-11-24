<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');

// Katalog (tampilan pembeli)
Route::get('/katalog', [ProductController::class, 'catalog'])->name('products.catalog');

// CRUD produk (admin & pegawai).
// Setelah modul Autentikasi (Ibnu) di-merge, aktifkan middleware di bawah:
// Route::middleware(['auth'])->group(function () {
Route::resource('products', ProductController::class)->except('show');
Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])
    ->name('products.stock');
// });