<?php

namespace App\Actions\Auth;

use App\Models\ClientKey;
use App\Traits\ApiResponseTrait;

class ValidateClientKeyAction
{
    use ApiResponseTrait;

    public function execute($clientKey)
    {
        $validateKey = ClientKey::where('private_key', $clientKey)->first();

        if (is_null($validateKey)) {
            \abort(422, "Client / company with this key does not exist" . $clientKey);
        }

        return $this->okResponse($validateKey);
    }
}