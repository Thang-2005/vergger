<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ResetPasswordController;
use App\Http\Controllers\Client\ForgotPasswordController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\ProductController;


Route::get('/', function () {
    return view('clients.pages.home');
})->name('home');

Route::get('/about', function () {
    return view('clients.pages.about');
})->name('about');

Route::get('/service', function () {
    return view('clients.pages.service');
})->name('service');

Route::get('/team', function () {
    return view('clients.pages.team');
})->name('team');

Route::get('/faq', function () {
    return view('clients.pages.faq');
})->name('faq');

Route::get('/shop', function () {
    return view('clients.pages.shop');
})->name('shop');

Route::get('/contact', function () {
    return view('clients.pages.contact');
})->name('contact');

Route::get('/wishlist', function () {
    return view('clients.pages.wishlist');
})->name('wishlist');

Route::get('/cart', function () {
    return view('clients.pages.cart');
})->name('cart');
//home
Route::get('/', [HomeController::class, 'index'] )->name('home');

Route::middleware('guest')->group(function () {
    // Các route dành cho khách chưa đăng nhập
Route::get('/register', [AuthController::class, 'show_register_form'] )->name('register_home');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/login', [AuthController::class, 'show_login_customer'])->name('login');
Route::post('/login', [AuthController::class, 'login_customer'])->name('login.customer');

Route::get('/forgot-password',[ForgotPasswordController::class,'show_forgot_password'])->name('password.request');

Route::post('/forgot-password',[ForgotPasswordController::class,'send_reset_link'])->name('password.email');

Route::get('/reset-password/{token}',[ResetPasswordController::class,'show_reset_form'])->name('password.reset');

Route::post('/reset-password',[ResetPasswordController::class,'reset_password'])->name('password.update');

});

//kích hoat tài khoản
Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate');

Route::middleware('auth.customer')->group(function () {
    // Các route dành cho khách đã đăng nhập
    Route::post('/logout-customer', [AuthController::class, 'logout_customer'])->name('logout.customer');
    Route::prefix('account')->group(function () {
            Route::get('/', [AccountController::class, 'show_account'])->name('account');
            Route::put('/update-profile', [AccountController::class, 'update_profile'])->name('account.update_profile');
            Route::put('/change-password', [AccountController::class, 'change_password'])->name('account.change_password');

            Route::post('/add-address', [AccountController::class, 'add_address'])->name('account.add_address');
            Route::delete('/delete-address/{id}', [AccountController::class, 'delete_address'])->name('account.delete_address');
            Route::put('/set-default-address/{id}', [AccountController::class, 'set_default_address'])->name('account.set_default_address');

    });

});

Route::get('/product', [ProductController::class, 'show_product'])->name('product');
Route::get('category/{id}', [ProductController::class, 'show_product'])->name('category');
Route::get('products/filter', [ProductController::class, 'filter_products'])->name('product.filter');
Route::get('/products/search', [ProductController::class, 'search_products'])->name('products.search');
Route::get('/products/filter', function(\Illuminate\Http\Request $request) {
    try {
        return app(\App\Http\Controllers\Client\ProductController::class)->filter_products($request);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file'  => basename($e->getFile()),
            'line'  => $e->getLine()
        ], 500);
    }
});