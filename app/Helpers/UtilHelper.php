<?php

namespace App\Helpers;

class UtilHelper
{
    public static function generateApiKey()
    {
        $publicKey = 'pub_'.static::generateApiToken();
        $privateKey = 'priv_'.static::generateApiToken();

        return [
            'public_key' => $publicKey,
            'private_key' => $privateKey,
        ];
    }

    public static function generateApiToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
