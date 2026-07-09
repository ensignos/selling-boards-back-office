<?php

namespace Database\Factories;
    
use App\Models\Store;
use App\Models\StoreVariant;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreVariant>
 */
class StoreVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'variant_id' => Variant::factory(),
            'merchant_id' => fn (array $attributes) => Variant::find($attributes['variant_id'])->merchant_id,
            'store_id' => fn (array $attributes) => Store::factory()->create(['merchant_id' => $attributes['merchant_id']])->id,
            'pricing_type' => fake()->randomElement(['FIXED', 'WEIGHT']),
            'price' => fake()->randomFloat(6, 1, 100),
            'available_for_sale' => fake()->boolean(80),
            'optimal_stock' => fake()->numberBetween(0, 100),
            'low_stock' => fake()->numberBetween(1, 10),
        ];
    }
}
