<?php

use Illuminate\Support\Facades\Route;

// トップページ → 自作の index.blade.php に変更
Route::view('/', 'index');

// その他のモック画面
Route::view('/mobile-order', 'mobile-order');
Route::view('/orders', 'orders');
Route::view('/products', 'products');
Route::view('/customers', 'customers');
Route::view('/settings', 'settings');
