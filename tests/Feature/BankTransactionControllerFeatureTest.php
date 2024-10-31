<?php

namespace Tests\Feature;

use App\Models\BankTransaction;
use App\Models\User;
use App\Models\Client;
use App\Models\ClientKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertJson;

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

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'private_key' => $clientKey->private_key,
        ]);
    }

    public function test_index_returns_all_bank_transaction()
    {
        BankTransaction::factory()->count(5)->create();

        $response = $this->getJson('/api/bank-transaction');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonPath('message', 'Bank transactions retrieved successfully');
    }


    public function test_it_can_create_bank_transaction()
    {
        $request = [
            'amount' => 100,
            'transaction_type' => 'deposit',
            'transaction_date' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/bank-transaction', $request);

        $response->assertCreated()
            ->assertJsonPath('message', 'Bank transaction created successfully');
        $this->assertDatabaseHas('bank_transactions', $request);
    }

    public function test_it_can_show_a_bank_transaction()
    {
        $transaction = BankTransaction::factory()->create();

        $response = $this->getJson("api/bank-transaction/{$transaction->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'BankTransaction retrieved successfully')
            ->assertJsonPath('data.id', $transaction->id)
            ->assertJsonStructure(['data']);
    }

    public function test_it_can_update_a_bank_transaction()
    {
        $transaction = BankTransaction::factory()->create();

        $data = [
            'transaction_type' => 'withdrawal',
            'transaction_date' => now()->toDateString(),
        ];
        $response = $this->putJson("api/bank-transaction/{$transaction->id}", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Bank transaction updated successfully');
        $this->assertDatabaseHas('bank_transactions', $data);
    }

    public function test_it_can_delete_a_bank_transaction()
    {
        $transaction = BankTransaction::factory()->create();

        $response = $this->deleteJson("/api/bank-transaction/{$transaction->id}", []);

        $response->assertOk()
            ->assertJsonPath('message', 'Bank transaction deleted successfully');
    }
}
