<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Send a 200 Success response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function okResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(true, $message, $data);

        return response()->json($data, 200);
    }

    /**
     * Send a 201 Created response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createdResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(true, $message, $data);

        return response()->json($data, 201);
    }

    /**
     * Send a 400 Bad Request response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 400);
    }

    /**
     * Send a 404 Not Found response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFoundResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 404);
    }

    /**
     * Send a 400 Bad Request response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function badRequestResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 400);
    }

    /**
     * Send a 401 Unauthorized response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorizedResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 401);
    }

    /**
     * Send a 403 Forbidden response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 403);
    }

    /**
     * Send a 405 Method Not Found response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function methodNotAllowedResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 405);
    }

    /**
     * Send a 422 Validation error response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationErrorResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 422);
    }

    /**
     * Send a 500 Server Error response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function serverErrorResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 500);
    }

    /**
     * Send a 503 Service Unavailable response
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function ServiceUnavailableResponse(string $message, $data = null): JsonResponse
    {
        $data = $this->prepareApiResponse(false, $message, $data);

        return response()->json($data, 503);
    }

    /**
     * Send a response using the status code
     * @param int $statusCode
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse(int $statusCode, string $message, $data = null): JsonResponse
    {
        $status = $statusCode >= 200 && $statusCode <= 205;
        $responseData = $this->prepareApiResponse($status, $message, $data);

        return response()->json($responseData, $statusCode);
    }

    /**
     * Prepare response payload
     * @param bool $status
     * @param string $message
     * @param null $data
     * @return array
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
