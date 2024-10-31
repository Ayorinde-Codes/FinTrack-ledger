<?php

namespace Database\Factories;

use App\Models\BankTransaction;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BankTransactionFactory extends Factory
{
    protected $model = BankTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'transaction_type' => $this->faker->randomElement(['credit', 'debit']),
            'transaction_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
