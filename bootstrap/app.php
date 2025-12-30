<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'seller'=>\App\Http\Middleware\EnsureSeller::class,
        ]);
        // Add this:
        // $middleware->web(append: [
        //     ValidateCsrfToken::class.':except=api/auth/login',
        // ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
