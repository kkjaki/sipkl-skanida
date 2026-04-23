<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\TokenMismatchException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
        ]);

        // Required for Auth::logoutOtherDevices() to invalidate sessions on other devices
        $middleware->web(append: [
            AuthenticateSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Gracefully handle expired CSRF tokens (419 Page Expired).
        // Common causes: session timeout then logout, or double-click on login/form submit.
        // Instead of a dead-end 419 page, redirect back to login with a helpful message.
        $exceptions->render(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi telah berakhir, silakan muat ulang halaman.'], 419);
            }

            return redirect()->route('login')
                ->with('status', 'Sesi Anda telah berakhir. Silakan masuk kembali.');
        });
    })->create();
