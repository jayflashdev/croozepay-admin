<?php

use App\Exceptions\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class,
            'admin.guest' => \App\Http\Middleware\AdminGuest::class,
            'user' => \App\Http\Middleware\User::class,
            'suspend' => \App\Http\Middleware\Suspend::class,
            'maintenance' => \App\Http\Middleware\Maintenance::class,
            'api-auth' => \App\Http\Middleware\ApiAuth::class,
            'verify' => \App\Http\Middleware\UserVerify::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, Request $request) {
            return (new ExceptionHandler())->handle($exception, $request);
            // Default rendering for non-JSON requests
            return parent::render($request, $exception);
        });
    })->create();
