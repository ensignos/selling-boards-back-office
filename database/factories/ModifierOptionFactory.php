<?php

namespace Database\Factories;

use App\Models\Modifier;
use App\Models\ModifierOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ModifierOption>
 */
class ModifierOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modifier_id' => Modifier::factory(),
            'merchant_id' => fn (array $attributes) => Modifier::find($attributes['modifier_id'])->merchant_id,
            'name' => fake()->words(2, true),
            'price' => fake()->randomFloat(2, 0, 10),
            'position' => fake()->numberBetween(0, 20),
        ];
    }
}
