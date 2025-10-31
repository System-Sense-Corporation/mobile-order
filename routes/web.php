<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    // auth
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // pages
    Route::get('/', fn () => view('index'))->name('home');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/products', fn () => view('products'))->name('products');
    Route::get('/customers', fn () => view('customers'))->name('customers');
    Route::get('/admin/users', fn () => view('admin.users'))->name('admin.users');
    Route::get('/settings', fn () => view('settings'))->name('settings');

    // ✅ Profile (แก้ไข + บันทึก)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

Route::post('/locale', function (Request $request) {
    $availableLocales = config('app.available_locales', []);
    $locale = $request->input('locale');

    if (! in_array($locale, $availableLocales, true)) {
        $locale = config('app.locale');
    }

    session()->put('locale', $locale);
    return back();
})->name('locale.switch');
