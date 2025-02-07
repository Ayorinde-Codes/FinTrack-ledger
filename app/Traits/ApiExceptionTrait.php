<?php

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

trait ApiExceptionTrait
{
    public function renderApiException($exception)
    {
        $responseData = $this->prepareApiExceptionData($exception);
        $payload = Arr::except($responseData, 'statusCode');
        $statusCode = $responseData['statusCode'];

        return response()->json($payload, $statusCode);
    }

    /**
     * Generate the status code, message and data for a particular exception
     */
    private function prepareApiExceptionData(Throwable $exception): array
    {
        $responseData['status'] = false;
        $message = $exception->getMessage();

        if ($exception instanceof NotFoundHttpException) {
            $responseData['message'] = empty($message) ? 'Resource not found' : $message;
            $responseData['statusCode'] = 404;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $responseData['message'] = $message;
            $responseData['statusCode'] = 405;
        } elseif ($exception instanceof ModelNotFoundException) {
            $responseData['message'] = "Unable to locate the {$this->modelNotFoundMessage($exception)} you requested.";
            $responseData['statusCode'] = 404;
        } elseif ($exception instanceof AuthenticationException) {
            $responseData['message'] = 'Unauthenticated';
            $responseData['statusCode'] = 401;
        } elseif ($exception instanceof ValidationException) {
            $responseData['message'] = $message;
            $responseData['errors'] = $exception->validator->errors();
            $responseData['statusCode'] = 422;
        } else {
            $responseData['message'] = $this->prepareExceptionMessage($exception);
            $responseData['statusCode'] = ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : 500;

            if ($debug = $this->extractExceptionData($exception)) {
                $responseData['debug'] = $debug;
            }
        }

        return $responseData;
    }

    private function prepareExceptionMessage($exception)
    {
        $exceptionMessage = $exception->getMessage() ?? 'An unknown error occurred';

        if (Str::contains($exceptionMessage, 'syntax error')) {
            $exceptionMessage = 'Server error';
        }

        return $exceptionMessage;
    }

    private function modelNotFoundMessage(ModelNotFoundException $exception): string
    {
        if (! is_null($exception->getModel())) {
            return Str::lower(ltrim(preg_replace('/[A-Z]/', ' $0', class_basename($exception->getModel()))));
        }

        return 'resource';
    }

    /**
     * Extraction of the error message from the exception
     */
    private function extractExceptionData(Throwable $exception): array
    {
        if (config('app.debug') && ! ($exception instanceof HttpExceptionInterface)) {
            $data = [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => collect($exception->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ];
        } else {
            $data = [];
        }

        return $data;
    }
}
