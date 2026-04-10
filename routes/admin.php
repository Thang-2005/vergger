<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('admin')->group(function () {
   

    Route::middleware('check.auth.admin')->group(function () {
        
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware('auth.admin')->group(function () {
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/dashboard', function () {
            return view('admin.pages.dashboard');
        })->name('admin.dashboard');
    });

     Route::middleware('check.permission:manage_users')->group(function () {
        Route::get('/users',[UserController::class, 'index'])->name('admin.users');
        Route::post('/user/upgrade', [UserController::class, 'upgradeRole'])->name('admin.user.upgrade');
        Route::post('/user/downgrade', [UserController::class, 'downgradeRole'])->name('admin.user.downgrade');
        Route::post('/user/change-status', [UserController::class, 'changeStatus'])->name('admin.user.change_status');
    });
});

