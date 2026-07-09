<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'merchant_id' => fn (array $attributes) => Item::find($attributes['item_id'])->merchant_id,
            'sku' => fake()->unique()->bothify('SKU-#####'),
            'option1_value' => fake()->word(),
            'option2_value' => fake()->word(),
            'option3_value' => fake()->word(),
            'barcode' => fake()->ean13(),
            'cost' => fake()->randomFloat(3, 1, 50),
            'purchase_cost' => fake()->randomFloat(3, 1, 50),
            'default_pricing_type' => fake()->randomElement(['FIXED', 'WEIGHT']),
            'default_price' => fake()->randomFloat(3, 1, 100),
        ];
    }
}
