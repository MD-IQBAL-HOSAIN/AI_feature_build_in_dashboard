<?php

use App\Http\Middleware\admin;
use App\Http\Middleware\EmployeeRoleMiddleware;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\WorkerRoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$app = Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'admin.auth', 'session.validity.check'])
                ->prefix('admin')
                ->group(base_path('routes/backend.php'));
        }
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth'             => admin::class,
            'jwt.verify'             => JWTMiddleware::class,
            'worker.auth'            => WorkerRoleMiddleware::class,
            'employee.auth'          => EmployeeRoleMiddleware::class,
            'session.validity.check' => \App\Http\Middleware\CheckSessionValidity::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }
        });
    });

// ✅ IMPORTANT: Kernel bind
app()->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

// ✅ Finally return the app
return $app->create();
