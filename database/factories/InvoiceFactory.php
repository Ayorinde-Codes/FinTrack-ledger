<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\User;
use App\Enums\Status;
use Illuminate\Support\Str;

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
            'client_id' => Client::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'invoice_number' => 'INV' . date('Y') . str_pad(Str::random(5), 5, '0', STR_PAD_LEFT),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'due_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'status' => Status::ACTIVE,
            'next_invoice_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
