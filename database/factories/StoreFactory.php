<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
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
            'name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'region' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country_code' => fake()->countryCode(),
            'phone_number' => fake()->phoneNumber(),
            'domain_url' => fake()->domainName(),
            'lat_lng_point' => DB::raw(sprintf("ST_GeomFromText('POINT(%F %F)', 4326)", fake()->latitude(), fake()->longitude())),
        ];
    }
}
