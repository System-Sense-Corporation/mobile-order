<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');

// Switch language
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'th', 'ja'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('switch-lang');

// Guest (auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

// Authenticated
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::middleware('permission')->group(function () {
        // Pages
        Route::get('/', fn () => view('index'))->name('home');

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::post('/orders/export/email', [OrderController::class, 'emailExport'])->name('orders.email');
        Route::get('/orders/form', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::get('/products/form', [ProductController::class, 'create'])->name('products.form');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
        Route::get('/customers/form', [CustomerController::class, 'create'])->name('customers.form');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        // Admin Users (accept both USR-#### or numeric id)
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', AdminUserController::class)
                ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
                ->parameters(['users' => 'user'])
                ->where(['user' => '(USR-\d+|\d+)']);
        });

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Legacy redirects
    Route::redirect('/mobile-order', '/orders/form')->name('mobile-order.legacy');
    Route::redirect('/orders/create', '/orders/form');
});

// Locale switch (POST)
Route::post('/locale', function (Request $request) {
    $availableLocales = config('app.available_locales', []);
    $locale = $request->input('locale');

    if (! in_array($locale, $availableLocales, true)) {
        $locale = config('app.locale');
    }

    session()->put('locale', $locale);
    return back();
})->name('locale.switch');
