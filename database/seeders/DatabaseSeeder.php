<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientIp;
use App\Models\ClientKey;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create 3 companies
        $clients = Client::factory(3)->create();

        // For each client, generate an IP and a set of keys
        $clients->each(function ($client) {
            ClientIp::factory()->create(['client_id' => $client->id]);
            ClientKey::factory()->create(['client_id' => $client->id]);
        });

        // Roles and Users creation remains the same
        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'manager']);
        Role::factory()->create(['name' => 'user']);

        // Create users and assign random roles
        User::factory(5)->create()->each(function ($user) {
            $roles = Role::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $user->roles()->attach($roles);
        });
    }
}
