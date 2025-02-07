<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientKey;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseControllerFeatureTest extends TestCase
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
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
            'private_key' => $clientKey->private_key,
        ]);
    }

    /**
     * Test to get list of expenses.
     */
    public function test_should_retrieve_expense_list()
    {
        Expense::factory()->count(5)->create();

        $response = $this->getJson('/api/expenses');

        $response->assertOk()
            ->assertJsonPath('message', 'Expenses retrieved successfully')
            ->assertJsonStructure(['data']);
    }

    public function test_it_should_create_expense()
    {
        $data = [
            'expense_category' => 'Utilities',
            'amount' => 100,
            'receipt' => 'recxx',
        ];

        $response = $this->postJson('/api/expenses', $data);

        $response->assertCreated()
            ->assertJsonPath('message', 'Expense created successfully');
        $this->assertDatabaseHas('expenses', $data);
    }

    public function test_it_should_show_a_expense()
    {
        $expense = Expense::factory()->create();

        $response = $this->getJson("/api/expenses/{$expense->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'Expense retrieved successfully')
            ->assertJsonPath('data.id', $expense->id)
            ->assertJsonStructure(['data']);
    }

    public function test_it_can_update_a_expense()
    {
        $expense = Expense::factory()->create();

        $data = [
            'expense_category' => 'Travels',
            'amount' => 50,
            'receipt' => 'recxx',
        ];

        $response = $this->putJson("/api/expenses/{$expense->id}", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Expense updated successfully');
        $this->assertDatabaseHas('expenses', $data);
    }

    public function test_it_can_delete_a_expense()
    {
        $expense = Expense::factory()->create();

        $response = $this->deleteJson("/api/expenses/{$expense->id}", []);

        $response->assertOk()
            ->assertJsonPath('message', 'Expense deleted successfully');
    }
}
