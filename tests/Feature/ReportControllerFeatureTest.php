<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientKey;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportControllerFeatureTest extends TestCase
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

    public function test_it_should_list_reports()
    {
        Report::factory()->count(5)->create();

        $response = $this->getJson('/api/report');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data'])
            ->assertJsonPath('message', 'Report retrieved successfully');
    }

    public function test_it_should_generate_report()
    {

        Report::factory()->count(5)->create();

        $report = [
            'report_type' => 'payroll'
        ];

        $response = $this->postJson('/api/report/generate', $report);
    }
}