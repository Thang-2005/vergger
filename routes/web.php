<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AuthController;

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


Route::get('/register', [AuthController::class, 'show_register_form'] )->name('register_home');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/login', [AuthController::class, 'show_login_customer'])->name('login');
Route::post('/login', [AuthController::class, 'login_customer'])->name('login.customer');

Route::get('/resrt-password', [AuthController::class, 'show_resrt_password'])->name('password.reset');


