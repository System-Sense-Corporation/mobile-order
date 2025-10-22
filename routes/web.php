<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('index'))->name('home');
Route::get('/mobile-order', fn () => view('mobile-order'))->name('mobile-order');
Route::get('/orders', fn () => view('orders'))->name('orders');
Route::get('/products', fn () => view('products'))->name('products');
Route::get('/customers', fn () => view('customers'))->name('customers');
Route::get('/settings', fn () => view('settings'))->name('settings');
