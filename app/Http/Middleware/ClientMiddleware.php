<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponseTrait;
use App\Actions\Auth\ValidateClientKeyAction;

class ClientMiddleware
{
    use ApiResponseTrait;

    protected $private_key = "private_key";

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $private_key = $request->header($this->private_key);

        if (!$private_key)
            return $this->unauthorizedResponse("Client private_key is missing.");

        if (!$response = $this->validateClientKey($private_key))
            return $this->unauthorizedResponse("Client private_key invalid");

        dd($response);
        // $clientId = $response->client
        $request->merge(['private_key' => $private_key]);

        return $next($request);
    }

    private function validateClientKey($private_key)
    {
        return (new ValidateClientKeyAction())->execute($private_key);
    }
}
