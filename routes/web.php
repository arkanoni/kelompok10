<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');

Route::view('/katalog', 'products.catalog')->name('products.catalog');

Route::view('/products', 'products.index')->name('products.index');
Route::view('/products/create', 'products.create')->name('products.create');
Route::view('/products/edit', 'products.edit')->name('products.edit');