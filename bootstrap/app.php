<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ensure.is.admin' => App\Http\Middleware\EnsureIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (Throwable $exception, $request) {

            if ($exception instanceof HttpException) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], 400);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Resource not found for: '.basename($exception->getModel()),
                ], 400);
            }

            if ($exception instanceof InvalidArgumentException) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], 400);
            }

        });
    })->create();
