<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientKey;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $heders;

    protected function setUp(): void
    {
        parent::setUp();

        $client = Client::factory()->create();

        $clientKey = ClientKey::factory()->create(['client_id' => $client->id]);

        $user = User::factory()->create(['client_id' => $client->id]);

        $token = $user->createToken('MyApp')->plainTextToken;

        $this->actingAs($user);

        $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
            'private_key' => $clientKey->private_key,
        ]);
    }

    public function test_it_should_list_tax()
    {
        Tax::factory()->count(5)->create();

        $response = $this->getJson('/api/tax');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data'])
            ->assertJsonPath('message', 'Tax retrieved successfully');
    }

    public function test_it_should_show_tax()
    {
        $tax = Tax::factory()->create();

        $response = $this->getJson("/api/tax/$tax->id");
        $response->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonPath('message', 'Tax retrieved successfully');
    }

    public function test_it_should_create_tax()
    {
        $data = [
            'amount' => 150,
            'tax_type' => 'Sales Tax',
            'tax_date' => '2024-11-05 00:00:00',
        ];

        $response = $this->postJson('/api/tax', $data);
        $response->assertCreated()
            ->assertJsonPath('message', 'Tax created successfully');

        $this->assertDatabaseHas('taxes', $data);
    }

    public function test_it_should_update_tax()
    {
        $tax = Tax::factory()->create();

        $data = [
            'amount' => 150,
            'tax_type' => 'Income Tax',
        ];
        $response = $this->putJson("/api/tax/$tax->id", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Tax updated successfully');
    }

    public function test_it_should_destroy_tax()
    {
        $tax = Tax::factory()->create();

        $response = $this->deleteJson("/api/tax/$tax->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Tax deleted successfully');
    }
}
