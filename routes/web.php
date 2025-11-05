<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

// Route สำหรับเปิดหน้า Settings (GET)
// VVVVV พี่โดนัทแก้ชื่อ .name() ตรงนี้ ให้นะคะ VVVVV
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

// Route สำหรับกดปุ่มบันทึก (POST) <-- ตัวนี้แหละค่ะที่จะแก้ Error!
Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
Route::get('/lang/{locale}', function ($locale) {
    // เช็คว่าภาษาที่ขอมารองรับมั้ย
    if (in_array($locale, ['en', 'th', 'ja'])) {
        // ถ้าโอเค ก็เก็บลง session
        Session::put('locale', $locale);
        App::setLocale($locale); // ตั้งค่าภาษาสำหรับ request นี้เลย
    }
    return redirect()->back(); // กลับไปหน้าเดิมที่กดมา
})->name('switch-lang'); // ตั้งชื่อ route ว่า 'switch-lang'


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    // auth
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::middleware('permission')->group(function () {
        // pages
        Route::get('/', fn () => view('index'))->name('home');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::post('/orders/export/email', [OrderController::class, 'emailExport'])->name('orders.email');
        Route::get('/orders/form', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::get('/products/form', [ProductController::class, 'create'])->name('products.form');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
        Route::get('/customers/form', [CustomerController::class, 'create'])->name('customers.form');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/form', [AdminUserController::class, 'create'])->name('admin.users.form');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}', [AdminUserController::class, 'edit'])
            ->where('user', 'USR-[0-9]+')
            ->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])
            ->where('user', 'USR-[0-9]+')
            ->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])
            ->where('user', 'USR-[0-9]+')
            ->name('admin.users.destroy');

        //
        // VVVVV พี่โดนัทลบบรรทัดที่ซ้ำซ้อนตรงนี้ทิ้งไปแล้วนะคะ! VVVVV
        // Route::get('/settings', fn () => view('settings'))->name('settings');
        // ^^^^^ ลบทิ้งไปแล้ว! ^^^^^
        //

        // ✅ Profile (แก้ไข + บันทึก)
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile');

        Route::post('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
    });

    Route::redirect('/mobile-order', '/orders/form')->name('mobile-order.legacy');
    Route::redirect('/orders/create', '/orders/form');
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