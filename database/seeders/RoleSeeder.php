<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Enums\UserRole;

class RoleSeeder extends Seeder
{
    public function run()
    {
        foreach (UserRole::cases() as $role) {
            Role::create([
                'name' => $role->value,
            ]);
        }
    }
}
