<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $roles = Role::all();

        foreach ($users as $user) {
            // Attach a random role to each user
            $user->roles()->attach(
                $roles->random()->id
            );
        }
    }
}
