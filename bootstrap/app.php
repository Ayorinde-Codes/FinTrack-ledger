<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Traits\ApiExceptionTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
            // handle specific exceptions with custom error handler
            if (
                $exception instanceof NotFoundHttpException ||
                $exception instanceof MethodNotAllowedHttpException ||
                $exception instanceof ModelNotFoundException ||
                $exception instanceof AuthenticationException ||
                $exception instanceof ValidationException
            ) {

                // Handle the exceptions using ApiExceptionTrait
                return app(ApiExceptionTrait::class)->renderApiException($exception);
            }
        });
    })->create();