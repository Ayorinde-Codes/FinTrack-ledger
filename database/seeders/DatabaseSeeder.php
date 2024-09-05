<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create 3 companies
        Company::factory(3)->create();

        // Create 3 roles (Admin, Manager, User)
        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'manager']);
        Role::factory()->create(['name' => 'user']);

        // Create 5 users and assign them to random companies
        User::factory(5)->create()->each(function ($user) {
            // Assign random roles to the user
            $roles = Role::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $user->roles()->attach($roles);
        });
    }
}
