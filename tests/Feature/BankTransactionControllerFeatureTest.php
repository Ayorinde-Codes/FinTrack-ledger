<?php

namespace Tests\Feature;

use App\Models\BankTransaction;
use App\Models\User;
use App\Models\Client;
use App\Models\ClientKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BankTransactionControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();


        $client = Client::factory()->create();

        $clientKey = ClientKey::factory()->create(['client_id' => $client->id]);

        $user = User::factory()->create(['client_id' => $client->id]);

        $token = $user->createToken('MyApp')->plainTextToken;

        $this->actingAs(user: $user);

        $this->headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'private_key' => $clientKey->private_key,
        ];

    }

    public function test_index_returns_all_bank_transaction()
    {
        BankTransaction::factory()->count(5)->create();

        $response = $this->getJson('/api/bankTransaction', $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonPath('message', 'Bank transactions retrieved successfully');
    }
}
