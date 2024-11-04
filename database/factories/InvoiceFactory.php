<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\User;
use App\Models\Invoice;
use App\Enums\Status;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'due_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'status' => Status::ACTIVE,
            'next_invoice_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
