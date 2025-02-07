<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Client;
use App\Models\ClientKey;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceControllerFeatureTest extends TestCase
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

    public function test_it_should_list_invoice()
    {
        Invoice::factory()->count(5)->create();

        $response = $this->getJson('/api/invoice');

        $response->assertOk()
            ->assertJsonPath('message', 'Invoices retrieved successfully')
            ->assertJsonStructure(['data']);
    }

    public function test_it_should_show_an_invoice()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->getJson("/api/invoice/$invoice->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Invoice retrieved successfully')
            ->assertJsonStructure(['data']);
    }

    public function test_it_should_create_invoice()
    {
        $data = [
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => 100,
            'due_date' => '2024-11-05 00:00:00',
            'status' => Status::ACTIVE,
            'next_invoice_date' => '2024-11-05 00:00:00',
        ];

        $response = $this->postJson('/api/invoice', $data);

        $response->assertCreated()
            ->assertJsonPath('message', 'Invoice created successfully');

        $this->assertDatabaseHas('invoices', data: $data);
    }

    public function test_it_should_update_invoice()
    {
        $invoice = Invoice::factory()->create();

        $data = [
            'recurrence' => 'monthly',
        ];

        $response = $this->putJson("/api/invoice/$invoice->id", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Invoice updated successfully');

        $this->assertDatabaseHas('invoices', $data);
    }

    public function test_it_should_delete_invoice()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->deleteJson("/api/invoice/$invoice->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Invoice deleted successfully');
    }
}
