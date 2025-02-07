<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'expense_category' => $this->faker->randomElement(['Office Supplies', 'Travel', 'Utilities']),
            'receipt' => Str::random(10),
        ];
    }
}
