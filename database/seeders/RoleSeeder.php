<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

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
