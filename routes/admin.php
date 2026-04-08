<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactController;

Route::prefix('admin')->group(function () {
   Route::get('/dashboard', function () {
       return view('admin.pages.dashboard');
   })->name('admin.dashboard');
});