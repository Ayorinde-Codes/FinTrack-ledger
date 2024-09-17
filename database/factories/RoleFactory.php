<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\UserRole;
use App\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        // Randomly pick a role from the UserRoles enum
        $roles = UserRole::cases();
        $role = $this->faker->randomElement($roles);

        return [
            'name' => $role->value,
        ];
    }
}
