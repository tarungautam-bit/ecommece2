<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CashbackController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\WalletTransactionController as WalletController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Middleware\AdminMiddleware;

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Public routes
Route::get('/', [ProductController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::get('wallet_transactions', [WalletController::class, 'index'])->name('wallet.index');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('checkout/payment/callback', [CheckoutController::class, 'paymentCallback'])->name('checkout.paymentCallback');


});
Route::get('/captcha', [RegisterController::class, 'generate_captcha']);

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('products', AdminProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    Route::resource('orders', AdminOrderController::class)->names([
        'index' => 'admin.orders.index',
        'create' => 'admin.orders.create',
        'store' => 'admin.orders.store',
        'show' => 'admin.orders.show',
        'edit' => 'admin.orders.edit',
        'update' => 'admin.orders.update',
        'destroy' => 'admin.orders.destroy',
    ]);
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    Route::post('users/{user}/credit', [UserController::class, 'credit'])->name('users.credit');
    Route::post('users/{user}/debit', [UserController::class, 'debit'])->name('users.debit');

    Route::resource('cashback', CashbackController::class)->names([
        'index' => 'admin.cashback.index',
        'create' => 'admin.cashback.create',
        'store' => 'admin.cashback.store',
        'show' => 'admin.cashback.show',
        'edit' => 'admin.cashback.edit',
        'update' => 'admin.cashback.update',
        'destroy' => 'admin.cashback.destroy',
    ]);
    Route::resource('wallet_transactions', WalletTransactionController::class)->only(['index', 'destroy'])->names([
        'index' => 'admin.wallet_transactions.index',
        'destroy' => 'admin.wallet_transactions.destroy',
    ]);
});

Route::get('admin/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
Route::post('admin/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('admin.logout');
