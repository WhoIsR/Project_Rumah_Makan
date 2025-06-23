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

        // =======================================================
        // ==> TAMBAHKAN KODE INI DI DALAM FUNGSI MIDDLEWARE <==
        // =======================================================
        // Ini adalah cara kita memberi "nama panggilan" ke middleware kita.
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        // =======================================================
        // =======================================================

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
