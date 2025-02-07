<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_all_roles()
    {
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Roles gotten successfully',
            ])
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ]);
    }
}
