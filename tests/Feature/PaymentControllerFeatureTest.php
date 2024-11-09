<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientKey;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentControllerFeatureTest extends TestCase
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
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'private_key' => $clientKey->private_key,
        ]);
    }

    public function test_it_should_list_payment()
    {
        Invoice::factory()->count(5)->create();
        Payment::factory()->count(5)->create();

        $response = $this->getJson('/api/payment');

        $response->assertOk()
            ->assertJsonPath('message', 'Payments retrieved successfully')
            ->assertJsonStructure(['data']);
    }

    public function test_it_should_show_a_payment()
    {
        $invoice = Invoice::factory()->create();
        $payment = Payment::factory()->create(['invoice_id' => $invoice->id]);

        $response = $this->getJson("/api/payment/$payment->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Payment retrieved successfully')
            ->assertJsonStructure(['data']);

        $this->assertDatabaseHas($payment);
    }

    public function test_it_should_create_payment()
    {
        $invoice = Invoice::factory()->create();
        $data = [
            'invoice_id' => $invoice->id,
            'amount' => 100,
            'payment_date' => '2024-11-05 00:00:00',
            'payment_method' => 'Cash',
        ];

        $response = $this->postJson('/api/payment', $data);

        $response->assertCreated()
            ->assertJsonPath('message', 'Payment created successfully');

        $this->assertDatabaseHas('payments', $data);
    }

    public function test_it_should_update_payment()
    {
        $invoice = Invoice::factory()->create();
        $payment = Payment::factory()->create(['invoice_id' => $invoice->id]);

        $data = [
            'amount' => 150,
            'payment_method' => 'Paypal',
        ];

        $response = $this->putJson("/api/payment/$payment->id", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Payment updated successfully');
    }

    public function test_it_should_delete_payment()
    {
        $invoice = Invoice::factory()->create();
        $payment = Payment::factory()->create(['invoice_id' => $invoice->id]);

        $response = $this->deleteJson("/api/payment/$payment->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Payment deleted successfully');
    }
}
