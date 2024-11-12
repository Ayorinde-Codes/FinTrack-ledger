<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payroll>
 */
class PayrollFactory extends Factory
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
            'user_id' => User::factory(),
            'salary' => $this->faker->randomFloat(2, 10, 10000),
            'payment_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'taxes' => [
                'federal_tax' => $this->faker->randomFloat(2, 100, 500),
                'state_tax' => $this->faker->randomFloat(2, 50, 300),
                'medicare' => $this->faker->randomFloat(2, 20, 150),
                'social_security' => $this->faker->randomFloat(2, 30, 200),
            ],
        ];
    }
}
