<?php

namespace Database\Factories;

use App\Helpers\UtilHelper;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientKey>
 */
class ClientKeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clientKey = UtilHelper::generateApiKey();

        return [
            'client_id' => Client::inRandomOrder()->first()->id,
            'private_key' => $clientKey['private_key'],
            'public_key' => $clientKey['public_key'],
        ];
    }
}
