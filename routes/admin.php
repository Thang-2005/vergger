<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;

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

     Route::middleware('check.permission:users.view')->group(function () {
        Route::get('/users',[UserController::class, 'index'])->name('admin.users');
    });

    Route::middleware('check.permission:users.manage')->group(function () {
        Route::post('/user/upgrade', [UserController::class, 'upgradeRole'])->name('admin.user.upgrade');
        Route::post('/user/downgrade', [UserController::class, 'downgradeRole'])->name('admin.user.downgrade');
        Route::post('/user/change-status', [UserController::class, 'changeStatus'])->name('admin.user.change_status');
    });

    Route::middleware('check.permission:categories.view')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    });

    Route::middleware('check.permission:categories.create')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    });

    Route::middleware('check.permission:categories.update')->group(function () {
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    });

    Route::middleware('check.permission:categories.toggle_status')->group(function () {
        Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle-status');
    });

    Route::middleware('check.permission:categories.delete')->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });

    Route::middleware('check.permission:manage_permissions')->group(function () {
        Route::get('/roles', [PermissionController::class, 'roleIndex'])->name('admin.roles.index');
        Route::post('/roles', [PermissionController::class, 'storeRole'])->name('admin.roles.store');
        Route::put('/roles/{role}', [PermissionController::class, 'updateRole'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [PermissionController::class, 'destroyRole'])->name('admin.roles.destroy');

        Route::get('/permissions', [PermissionController::class, 'index'])->name('admin.permissions');
        Route::post('/permissions/update-role/{role}', [PermissionController::class, 'updateRolePermissions'])->name('admin.permissions.update-role');
        Route::get('/permissions/user/{user}', [PermissionController::class, 'manageUserPermissions'])->name('admin.permissions.user');
        Route::get('/permissions/create', [PermissionController::class, 'createPermission'])->name('admin.permissions.create');
        Route::post('/permissions/store', [PermissionController::class, 'storePermission'])->name('admin.permissions.store');
    });


       Route::middleware('check.permission:manage_products')->group(function () {
    Route::get('/products', [ProductController::class, 'show_products'])->name('admin.products.list');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    });

    Route::middleware('check.permission:manage_orders')->group(function () {
    Route::get('/orders', [OrderController::class, 'show_orders'])->name('admin.orders.list');
    Route::get('/orders/{order}', [OrderController::class, 'detail_order'])->name('admin.orders.detail');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/orders/{order}/send-invoice', [OrderController::class, 'send_invoice'])->name('admin.orders.send-invoice');

    });
});

