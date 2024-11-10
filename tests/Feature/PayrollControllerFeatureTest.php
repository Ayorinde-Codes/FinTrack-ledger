<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientKey;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PayrollControllerFeatureTest extends TestCase
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
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'private_key' => $clientKey->private_key,
        ]);
    }

    public function test_it_should_list_all_payroll()
    {
        Payroll::factory()->count(5)->create();

        $response = $this->getJson('/api/payroll');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('message', 'Payroll retrieved successfully');
    }

    public function test_it_should_show_payroll()
    {
        $payroll = Payroll::factory()->create();

        $response = $this->getJson("/api/payroll/$payroll->id");

        $response->assertOk()
            ->assertJsonPath('data.id', $payroll->id)
            ->assertJsonPath('message', 'Payroll retrieved successfully');
    }

    public function test_it_should_create_payroll()
    {
        $payroll = [
            'salary' => 1000,
            'payment_date' => '2024-11-05 00:00:00',
        ];

        $response = $this->postJson('/api/payroll', $payroll);

        $response->assertCreated()
            ->assertJsonPath('message', 'Payroll created successfully');

        $this->assertDatabaseHas('payrolls', $payroll);
    }

    public function test_it_should_update_payroll()
    {
        $payroll = Payroll::factory()->create();

        $data = [
            'salary' => 2000,
            'payment_date' => '2024-11-06 00:00:00',
        ];

        $response = $this->putJson("/api/payroll/$payroll->id", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Payroll updated successfully');
    }

    public function test_it_should_delete_payroll()
    {
        $payroll = Payroll::factory()->create();

        $response = $this->deleteJson("/api/payroll/$payroll->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Payroll deleted successfully');
    }
}
