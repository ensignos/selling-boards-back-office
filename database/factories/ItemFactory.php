<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'merchant_id' => fn (array $attributes) => Category::find($attributes['category_id'])->merchant_id,
            'supplier_id' => Supplier::factory(),
            'handle' => fake()->unique()->slug(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'track_stock' => fake()->boolean(),
            'sold_by_weight' => fake()->boolean(),
            'is_composite' => fake()->boolean(),
            'use_production' => fake()->boolean(),
            'form' => fake()->randomElement(['EACH', 'BOTTLE', 'CAN', 'DRAUGHT', 'WEIGHT']),
            'colour' => fake()->randomElement(['RED', 'GREEN', 'BLUE', 'GREY', 'YELLOW', 'ORANGE', 'PURPLE', 'PINK']),
            'image_url' => fake()->imageUrl(),
            'option1_name' => fake()->word(),
            'option2_name' => fake()->word(),
            'option3_name' => fake()->word(),

        ];
    }
}
