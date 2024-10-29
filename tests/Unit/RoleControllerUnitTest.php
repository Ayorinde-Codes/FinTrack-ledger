<?php

namespace Tests\Unit;


use App\Http\Controllers\V1\RoleController;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Mockery;


class RoleControllerUnitTest extends TestCase
{
    public function test_it_returns_all_roles()
    {
        // Arrange: Mock the Role model and Resource Collection
        $roles = Role::factory()->count(3)->make(); // Mock collection of roles
        $rolesResource = RoleResource::collection($roles);

        $roleMock = Mockery::mock(Role::class);
        $roleMock->shouldReceive('all')->andReturn($roles);

        $controller = new RoleController();

        $response = $controller->__invoke(new Request());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Roles gotten successfully', $responseData['message']);
        $this->assertEquals($rolesResource->resolve(), $responseData['data']);
    }
}
