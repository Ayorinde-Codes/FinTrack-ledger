<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Mockery;
use Illuminate\Support\Facades\DB;

class AuthControllerUnitTest extends TestCase
{
    /** @test */
    public function it_can_register_a_user()
    {
        $data = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'avatar' => 'avatar.png',
            'client_id' => 1,
        ];

        $request = AuthRequest::create('/register', 'POST', $data);

        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($input) use ($data) {
                return $input['email'] === $data['email'];
            }))
            ->andReturn((object) ['id' => 1, 'name' => 'John Doe']);

        $userMock->shouldReceive('roles->attach')->once();

        // Mocking other dependencies
        Hash::shouldReceive('make')->once()->with($data['password'])->andReturn('hashedPassword');
        $userMock->shouldReceive('createToken')->once()->andReturn((object) ['plainTextToken' => 'fakeToken']);

        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) use ($userMock) {
            $callback();
        });

        // Call the controller method
        $controller = new AuthController();
        $response = $controller->register($request);

        // Assert that the response contains the expected values
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('User account created successfully', $responseData['message']);
    }

    /** @test */
    public function it_can_login_user()
    {
        $data = [
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $request = Request::create('/login', 'POST', $data);

        // Mock the Auth attempt
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => $data['email'], 'password' => $data['password']])
            ->andReturnTrue();

        $userMock = Mockery::mock(User::class);
        $userMock->name = 'John Doe';
        $userMock->username = 'johndoe';

        // Mock Auth::user
        Auth::shouldReceive('user')->once()->andReturn($userMock);
        $userMock->shouldReceive('createToken')->once()->andReturn((object) ['plainTextToken' => 'fakeToken']);

        // Call the controller method
        $controller = new AuthController();
        $response = $controller->login($request);

        // Assert that the response contains the expected values
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('User login successfully.', $responseData['message']);
    }

    /** @test */
    public function it_cannot_login_with_invalid_credentials()
    {
        $data = [
            'email' => 'john@example.com',
            'password' => 'invalidPassword',
        ];

        $request = Request::create('/login', 'POST', $data);

        // Mock the Auth attempt to return false
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => $data['email'], 'password' => $data['password']])
            ->andReturnFalse();

        // Call the controller method
        $controller = new AuthController();
        $response = $controller->login($request);

        // Assert that the response contains the expected values
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Invalid Credentials', $responseData['message']);
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        $userMock = Mockery::mock(User::class);
        $requestMock = Mockery::mock(Request::class);

        $userMock->shouldReceive('tokens->delete')->once();
        $userMock->shouldReceive('currentAccessToken->delete')->once();
        $requestMock->shouldReceive('user')->andReturn($userMock);

        // Call the controller method
        $controller = new AuthController();
        $response = $controller->logout($requestMock);

        // Assert that the response contains the expected values
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('You have been successfully logged out.', $responseData['message']);
    }
}
