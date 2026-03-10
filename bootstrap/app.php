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
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);

        // ✅ Ganti cara lama (deprecated) dengan alias seperti ini
        $middleware->alias([
            'auth'          => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.jwt'      => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,      // ← bukan deprecated
            'jwt.refresh'   => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,      // ← bukan deprecated
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();