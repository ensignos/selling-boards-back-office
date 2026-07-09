<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Modifier;
use App\Models\ModifierOption;
use App\Models\Item;
use App\Models\Employee;
use App\Models\Store;
use App\Models\Tax;
use App\Models\Variant;
use App\Models\StoreVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $merchant = Merchant::factory()->create();
        $suppliers = Supplier::factory(3)->create();
        $categories = Category::factory(5)->for($merchant)->create();
        $modifiers = Modifier::factory(4)->for($merchant)->create();

        $modifiers->each(fn (Modifier $modifier) => ModifierOption::factory(3)->for($modifier)->create());

        $items = $categories->flatMap(fn (Category $category) => Item::factory(4)
            ->for($category)
            ->for($suppliers->random())
            ->create());

        Employee::factory(5)->for($merchant)->create();
        $stores = Store::factory(3)->for($merchant)->create();
        $taxes = Tax::factory(2)->for($merchant)->create();

        $variants = $items->flatMap(fn (Item $item) => Variant::factory(2)
            ->for($item)
            ->create());
        
        $variants->each(function (Variant $variant) use ($stores) {
            $stores->each(fn (Store $store) => StoreVariant::factory()
                ->for($variant)
                ->for($store)
                ->create());
        });

        $employees = Employee::all();

        $stores->each(function (Store $store) use ($employees, $modifiers, $taxes) {
            $store->employees()->attach($employees->random(min(2, $employees->count())));
            $store->modifiers()->attach($modifiers->random(min(2, $modifiers->count())));
            $store->taxes()->attach($taxes);
        });
    }
}
