<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\customauth;
use App\Http\Middleware\set_lang;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'customauth' => CustomAuth::class,
            "set_lang" => set_lang::class,
            'check.role' => \App\Http\Middleware\CheckRole::class,
        ]);
        $middleware->web([
        set_lang::class, // يخلي كل web routes يعدوا على middleware ده
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
