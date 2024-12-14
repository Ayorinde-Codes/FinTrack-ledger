<?php

namespace App\Services\Http;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Illuminate\Http\Client\RequestException;

abstract class Service
{
    protected $response;
    protected $headers = [];
    public $response_status = 0;

    abstract protected function baseUri();

    public function __construct()
    {
        $this->headers = $this->defaultHeaders();
    }

    public function makeRequest(string $method, string $uri, array $options = [])
    {
        $this->validateRequestMethod($method);
        $url = $this->baseUri() . $uri;

        $options = $this->mergeHeaders($options);

        try {
            $response = Http::withOptions($options)->{$method}($url);
            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    protected function validateRequestMethod($method)
    {
        $valid_methods = ['get', 'post', 'put', 'delete', 'patch'];
        if (!in_array(strtolower($method), $valid_methods)) {
            throw new InvalidArgumentException("{$method} is not a valid request type");
        }
    }

    protected function defaultHeaders()
    {
        return [];
    }

    protected function mergeHeaders(array $options)
    {
        $options['headers'] = array_merge($this->headers, $options['headers'] ?? []);
        return $options;
    }

    protected function handleResponse($response)
    {
        $this->response_status = $response->status();
        return [
            'status' => $this->response_status,
            'data' => $response->json(),
        ];
    }

    protected function handleException(RequestException $exception)
    {
        $response = $exception->response;
        $this->response_status = $response ? $response->status() : $exception->getCode();
        return [
            'status' => $this->response_status,
            'message' => $exception->getMessage(),
            'error' => $response ? $response->json() : $exception->getMessage(),
        ];
    }

    public function __call($method, $arguments)
    {
        $this->validateRequestMethod($method);
        return $this->makeRequest($method, ...$arguments);
    }
}
