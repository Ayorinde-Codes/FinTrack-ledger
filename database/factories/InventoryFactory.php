<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => $this->faker->randomElement(['laptop', 'papers']),
            'quantity' => $this->faker->randomNumber(2),
            'price' => $this->faker->randomFloat(2, 10, 10000),
        ];
    }
}
