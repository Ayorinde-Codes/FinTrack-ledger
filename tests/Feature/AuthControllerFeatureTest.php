<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_register_a_user()
    {
        // Ensure a client exists
        $client = Client::factory()->create();

        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'avatar' => 'http://example.com/avatar.png',
            'client_id' => $client->id,
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.username', 'johndoe');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function it_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $user->name);
    }

    public function it_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'Invalid Credentials');
    }

    public function it_can_logout_a_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'You have been successfully logged out.');
    }
}
