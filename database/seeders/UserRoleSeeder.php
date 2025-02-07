<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

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
