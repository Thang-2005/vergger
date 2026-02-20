<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ResetPasswordController;
use App\Http\Controllers\Client\ForgotPasswordController;

Route::get('/', function () {
    return view('clients.pages.home');
});

Route::get('/about', function () {
    return view('clients.pages.about');
});

Route::get('/service', function () {
    return view('clients.pages.service');
});

Route::get('/team', function () {
    return view('clients.pages.team');
});

Route::get('/faq', function () {
    return view('clients.pages.faq');
});
//home
Route::get('/', [HomeController::class, 'index'] )->name('home');




// register/login
Route::get('/register', [AuthController::class, 'show_register_form'] )->name('register_home');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/login', [AuthController::class, 'show_login_customer'])->name('login');
Route::post('/login', [AuthController::class, 'login_customer'])->name('login.customer');
Route::post('/logout-customer', [AuthController::class, 'logout_customer'])->name('logout.customer');

Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate');

Route::get('/account', [AuthController::class, 'show_account'])->name('account')->middleware('auth'); 

Route::get('/forgot-password',[ForgotPasswordController::class,'show_forgot_password'])->name('password.request');

Route::post('/forgot-password',[ForgotPasswordController::class,'send_reset_link'])->name('password.email');

Route::get('/reset-password/{token}',[ResetPasswordController::class,'show_reset_form'])->name('password.reset');

Route::post('/reset-password',[ResetPasswordController::class,'reset_password'])->name('password.update');