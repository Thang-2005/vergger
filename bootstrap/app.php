<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.customer' => \App\Http\Middleware\CheckCustomerAuth::class,
            'auth.admin' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
            'check.auth.admin' => \App\Http\Middleware\RedirectIfAuthentcatedAdmin::class,
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            'default.admin.data' => \App\Http\Middleware\DefaultAdminData::class,
        ]);

        // Add SetLocale middleware to all web requests
        $middleware->web(
            \App\Http\Middleware\SetLocale::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
