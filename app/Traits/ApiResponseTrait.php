<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Send a 200 Success response
     *
     * @param  null  $data
     */
    public function okResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(true, $message, $data);

        return response()->json($data, 200);
    }

    /**
     * Send a 201 Created response
     *
     * @param  null  $data
     */
    public function createdResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(true, $message, $data);

        return response()->json($data, 201);
    }

    /**
     * Send a 400 Bad Request response
     *
     * @param  null  $data
     */
    public function errorResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 400);
    }

    /**
     * Send a 404 Not Found response
     *
     * @param  null  $data
     */
    public function notFoundResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 404);
    }

    /**
     * Send a 400 Bad Request response
     *
     * @param  null  $data
     */
    public function badRequestResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 400);
    }

    /**
     * Send a 401 Unauthorized response
     *
     * @param  null  $data
     */
    public function unauthorizedResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 401);
    }

    /**
     * Send a 403 Forbidden response
     *
     * @param  null  $data
     */
    public function forbiddenResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 403);
    }

    /**
     * Send a 405 Method Not Found response
     *
     * @param  null  $data
     */
    public function methodNotAllowedResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 405);
    }

    /**
     * Send a 422 Validation error response
     *
     * @param  null  $data
     */
    public function validationErrorResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 422);
    }

    /**
     * Send a 500 Server Error response
     *
     * @param  null  $data
     */
    public function serverErrorResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 500);
    }

    /**
     * Send a 503 Service Unavailable response
     *
     * @param  null  $data
     */
    public function ServiceUnavailableResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 503);
    }

    /**
     * Send a response using the status code
     *
     * @param  null  $data
     */
    public function sendResponse(int $statusCode, string $message, $data = null): JsonResponse
    {
        $status = $statusCode >= 200 && $statusCode <= 205;
        $responseData = $this->prepareApiResponse($status, $message, $data);

        return response()->json($responseData, $statusCode);
    }

    /**
     * Prepare response payload
     *
     * @param  null  $data
     */
    public function prepareApiResponse(bool $status, string $message, $data = null): array
    {
        $responseData = ['status' => $status, 'message' => $message];

        if ($data) {
            $responseData['data'] = $data;
        }

        return $responseData;
    }
}
