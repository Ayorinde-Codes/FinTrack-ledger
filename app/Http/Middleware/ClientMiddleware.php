<?php

namespace App\Http\Middleware;

use App\Actions\Auth\ValidateClientKeyAction;
use App\Enums\UserRole;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    use ApiResponseTrait;

    protected $private_key = 'private_key';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $private_key = $request->header($this->private_key);

        if (! $private_key) {
            return $this->unauthorizedResponse('Client private_key is missing.');
        }

        if (! $response = $this->validateClientKey($private_key)) {
            return $this->unauthorizedResponse('Client private_key invalid');
        }

        $user = $request->user();

        if (in_array(UserRole::USER, $user->roles->pluck('name')->toArray())) {
            if ($user->id() !== $response->client_id) {
                return $this->unauthorizedResponse('User do not belong to this company');
            }
        }

        $request->merge([
            'private_key' => $private_key,
            'client_id' => $response->client_id,
        ]);

        return $next($request);
    }

    private function validateClientKey($private_key)
    {
        return (new ValidateClientKeyAction)->execute($private_key);
    }
}
