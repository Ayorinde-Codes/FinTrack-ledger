<?php

namespace App\Actions\Auth;

use App\Models\ClientKey;
use App\Traits\ApiResponseTrait;

class ValidateClientKeyAction
{
    use ApiResponseTrait;

    public function execute($clientKey)
    {
        $validateKey = ClientKey::wherePrivateKey($clientKey)->first();
        if (is_null($validateKey)) {
            \abort(422, "Client/ Company with this key: {$clientKey} does not exist");
        }

        return $validateKey;
    }
}
