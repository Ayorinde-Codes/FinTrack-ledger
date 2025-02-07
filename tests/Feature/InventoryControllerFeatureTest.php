<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientKey;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryControllerFeatureTest extends TestCase
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

        $this->actingAs($user);

        $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
            'private_key' => $clientKey->private_key,
        ]);
    }

    public function test_it_should_list_inventories()
    {
        Inventory::factory()->count(5)->create();

        $response = $this->getJson('/api/inventories');

        $response->assertOk()
            ->assertJsonPath('message', 'Inventories retrieved successfully')
            ->assertJsonStructure(['data']);
    }

    public function test_it_should_show_an_inventory()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->getJson("/api/inventories/$inventory->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Inventory retrieved successfully')
            ->assertJsonStructure(['data']);
    }

    public function test_it_should_create_inventory()
    {
        $data = [
            'product_name' => 'Printer',
            'quantity' => 10,
            'price' => 100,
        ];

        $response = $this->postJson('/api/inventories', $data);

        $response->assertCreated()
            ->assertJsonPath('message', 'Inventory created successfully');

        $this->assertDatabaseHas('inventories', $data);
    }

    public function test_it_should_update_inventory()
    {
        $inventory = Inventory::factory()->create();

        $data = [
            'product_name' => 'Laptop',
            'quantity' => 5,
            'price' => 1500,
        ];

        $response = $this->putJson("/api/inventories/$inventory->id", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Inventory updated successfully');

        $this->assertDatabaseHas('inventories', $data);
    }

    public function test_it_should_delete_inventory()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->deleteJson("/api/inventories/$inventory->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Inventory deleted successfully');
    }
}
