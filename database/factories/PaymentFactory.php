<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::inRandomOrder()->first()->id,
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'payment_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'payment_method' => $this->faker->randomElement(['Credit Card', 'Bank Transfer', 'Cash', 'PayPal']),
        ];
    }
}
