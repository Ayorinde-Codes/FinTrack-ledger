<?php

use App\Exceptions\ApiExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Exceptions\Handler as DefaultExceptionHandler;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, $request) {
            if ($request->is('api/*')) {
                // Use the ApiExceptionHandler class to handle exceptions
                return app(ApiExceptionHandler::class)->renderApiException($exception);
            }

            // Fallback to the default Laravel ExceptionHandler
            $defaultHandler = app(DefaultExceptionHandler::class);
            return $defaultHandler->render($request, $exception);
        });
    })->create();
