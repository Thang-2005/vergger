<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProfileController;




Route::prefix('admin')->group(function () {
   

    Route::middleware('check.auth.admin')->group(function () {
        
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::get('/', [AdminAuthController::class, 'showLoginForm']);

        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware(['auth.admin', 'default.admin.data'])->group(function () {
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/locale/{locale}', [LocaleController::class, 'change'])->name('admin.locale.change');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/profile', [AdminProfileController::class, 'show'])->name('admin.profile');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/profile/change-password', [AdminProfileController::class, 'changePassword'])->name('admin.profile.change-password');
    });

     Route::middleware(['check.permission:users.view', 'default.admin.data'])->group(function () {
        Route::get('/users',[UserController::class, 'index'])->name('admin.users');
    });

    Route::middleware(['check.permission:users.manage', 'default.admin.data'])->group(function () {
        Route::post('/user/upgrade', [UserController::class, 'upgradeRole'])->name('admin.user.upgrade');
        Route::post('/user/downgrade', [UserController::class, 'downgradeRole'])->name('admin.user.downgrade');
        Route::post('/user/change-status', [UserController::class, 'changeStatus'])->name('admin.user.change_status');
    });

    Route::middleware(['check.permission:categories.view', 'default.admin.data'])->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    });

    Route::middleware(['check.permission:categories.create', 'default.admin.data'])->group(function () {
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    });

    Route::middleware(['check.permission:categories.update', 'default.admin.data'])->group(function () {
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    });

    Route::middleware(['check.permission:categories.toggle_status', 'default.admin.data'])->group(function () {
        Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle-status');
    });

    Route::middleware(['check.permission:categories.delete', 'default.admin.data'])->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });

    Route::middleware(['check.permission:manage_permissions', 'default.admin.data'])->group(function () {
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


    Route::middleware(['check.permission:manage_products', 'default.admin.data'])->group(function () {
    Route::get('/products', [ProductController::class, 'show_products'])->name('admin.products.list');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}', [ProductController::class, 'detail'])->name('admin.products.detail');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/{product}/images', [ProductController::class, 'getProductImages'])->name('admin.products.images');

    });

    Route::middleware(['check.permission:manage_orders', 'default.admin.data'])->group(function () {
    Route::get('/orders', [OrderController::class, 'show_orders'])->name('admin.orders.list');
    Route::get('/orders/{order}', [OrderController::class, 'detail_order'])->name('admin.orders.detail');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/orders/{order}/send-invoice', [OrderController::class, 'send_invoice'])->name('admin.orders.send-invoice');

    });

    Route::middleware(['check.permission:manage_coupons', 'default.admin.data'])->group(function () {
    Route::get('/coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::post('/coupons', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('admin.coupons.update');
    Route::patch('/coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('admin.coupons.toggle');
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');

    });

    Route::middleware(['check.permission:manage_contacts', 'default.admin.data'])->group(function () {
        Route::get('/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');
        Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('admin.contacts.show');
        Route::post('/contacts/{contact}/reply', [ContactController::class, 'reply'])->name('admin.contacts.reply');
        Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');
    });

    
});

