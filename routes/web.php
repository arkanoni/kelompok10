<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', CategoryController::class)->middleware('auth');
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ControllerTesting;


Route::redirect('/', '/dashboard');

//tamu(belum login)
Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

// sudah login
Route::middleware('auth')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [AuthController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // KHUSUS ADMIN
    Route::middleware('role:admin')->group(function(){
    Route::resource('user', UserController::class)->except('show');
    Route::resource('categories', CategoryController::class)->only(['create', 'store', 'edit', 'update', 'destrory']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'export']);
    Route::get('/reports/inventory/export', [ReportController::class, 'export']);
    });

    //admin dan pegawai
    Route::middleware('role:admin,pegawai')->group(function(){
        Route::get('/categories', [CategoryController::class, 'index']);

        Route::get('/products/stock', [ProductController::class, 'stock']);
        Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock']);
        Route::resource('products', ProductController::class)->except('show');

        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        // Route::patch('/orders/{order}/status', [OrderController::class, 'export']);

        Route::get('/reports/inventory', [ReportController::class, 'inventory']);
        // Route::get('/reports/inventory/export', [ReportController::class, 'export']);
    });

    //khusus pembeli
    Route::middleware('role:user')->prefix('shop')->group(function () {
        Route::get('/', [ShopController::class, 'index']);
        Route::get('/checkout/{product}', [ShopController::class, 'checkout']);
        Route::post('/orders', [ShopController::class, 'store']);
        Route::get('/orders', [ShopController::class, 'orders']);
        Route::get('/orders/{order}', [ShopController::class, 'orderShow']);
    });
});


// reports
// Route::middleware('reports')->group(function(){
//     Route::get('/reports', [ReportController::class, 'index'])->name('reports');
// });
