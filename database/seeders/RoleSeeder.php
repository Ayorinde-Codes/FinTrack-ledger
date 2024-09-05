<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Enums\UserRoles;

class RoleSeeder extends Seeder
{
    public function run()
    {
        foreach (UserRoles::cases() as $role) {
            Role::create([
                'name' => $role->value,
            ]);
        }
    }
}
