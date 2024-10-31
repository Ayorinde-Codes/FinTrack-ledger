<?php

namespace Tests\Unit;

use App\Enums\UserRole;
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
    public function test_should_be_user(): void
    {

        $this->assertEquals(UserRole::USER->value, 'user');
    }

    public function test_should_be_admin(): void
    {
        $this->assertEquals(UserRole::ADMIN->value, 'admin');
    }

    public function test_should_be_manager()
    {
        $this->assertEquals(UserRole::MANAGER->value, 'manager');
    }
}
