<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Admin;
use App\Http\Middleware\SuperAdmin;
use App\Http\Middleware\Intern;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'super-admin' => SuperAdmin::class,
            'admin' => Admin::class,
            'intern' => Intern::class,
        ]); 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();