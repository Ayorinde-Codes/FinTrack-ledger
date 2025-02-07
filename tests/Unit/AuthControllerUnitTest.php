<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use Tests\TestCase;

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
