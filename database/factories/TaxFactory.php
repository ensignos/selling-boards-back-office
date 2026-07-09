<?php

namespace Database\Factories;

use App\Models\Tax;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tax>
 */
class TaxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'merchant_id' => Merchant::factory(),
            'type' => fake()->randomElement(['VAT', 'SALES', 'SERVICE']),
            'name' => fake()->words(2, true),
            'rate' => fake()->randomFloat(2, 0, 25),
        ];
    }
}
