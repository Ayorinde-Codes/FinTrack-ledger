<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
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
            'report_type' => $this->faker->randomElement(['financial', 'sales', 'inventory', 'payroll']),
            'data' => json_encode([
                'total_revenue' => $this->faker->randomFloat(2, 1000, 100000),
                'total_expenses' => $this->faker->randomFloat(2, 500, 50000),
                'profit' => $this->faker->randomFloat(2, 100, 50000),
            ]),
            'generated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
