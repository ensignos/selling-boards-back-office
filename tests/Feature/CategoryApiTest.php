<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_categories_for_a_merchant(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $categories = Category::factory()->count(3)->for($merchant)->create();

        $otherMerchant = Merchant::factory()->create();
        Category::factory()->count(2)->for($otherMerchant)->create();

        $response = $this->getJson("/api/merchants/{$merchant->id}/categories", [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        foreach ($categories as $category) {
            $response->assertJsonFragment([
                'id' => $category->id,
            ]);
        }
    }

    public function test_authenticated_user_can_create_a_category(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();

        $response = $this->postJson("/api/merchants/{$merchant->id}/categories", [
            'name' => 'Starters',
            'colour' => 'RED',
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'merchant_id' => $merchant->id,
            'name' => 'Starters',
            'colour' => 'RED',
        ]);
    }

    public function test_creating_a_category_without_a_name_fails_validation(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();

        $response = $this->postJson("/api/merchants/{$merchant->id}/categories", [
            'colour' => 'BLUE',
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_authenticated_user_can_view_a_single_category_for_a_merchant(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $category = Category::factory()->for($merchant)->create();

        $response = $this->getJson("/api/merchants/{$merchant->id}/categories/{$category->id}", [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $category->id]);
    }

    public function test_viewing_a_category_belonging_to_a_different_merchant_returns_404(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $otherMerchant = Merchant::factory()->create();
        $otherCategory = Category::factory()->for($otherMerchant)->create();

        $response = $this->getJson("/api/merchants/{$merchant->id}/categories/{$otherCategory->id}", [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_update_a_category(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $category = Category::factory()->for($merchant)->create([
            'name' => 'Starters',
            'colour' => 'RED',
        ]);

        $response = $this->patchJson("/api/merchants/{$merchant->id}/categories/{$category->id}", [
            'name' => 'Mains',
            'colour' => 'BLUE'
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Mains',
            'colour' => 'BLUE',
        ]);
    }

    public function test_updating_a_category_with_only_one_field_leaves_the_other_unchanged(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $category = Category::factory()->for($merchant)->create([
            'name' => 'Starters',
            'colour' => 'RED',
        ]);

        $response = $this->patchJson("/api/merchants/{$merchant->id}/categories/{$category->id}", [
            'colour' => 'BLUE',
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Starters',
            'colour' => 'BLUE', // Colour should be updated
        ]);
    }

    public function test_authenticated_user_can_delete_a_category(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $category = Category::factory()->for($merchant)->create();

        $response = $this->deleteJson("/api/merchants/{$merchant->id}/categories/{$category->id}", [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(204);

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_deleting_a_category_belonging_to_a_different_merchant_returns_404(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $otherMerchant = Merchant::factory()->create();
        $otherCategory = Category::factory()->for($otherMerchant)->create();

        $response = $this->deleteJson("/api/merchants/{$merchant->id}/categories/{$otherCategory->id}", [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(404);
    }

    public function test_updating_a_category_belonging_to_a_different_merchant_returns_404(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $otherMerchant = Merchant::factory()->create();
        $otherCategory = Category::factory()->for($otherMerchant)->create();

        $response = $this->patchJson("/api/merchants/{$merchant->id}/categories/{$otherCategory->id}", [
            'name' => 'Updated Name',
            'colour' => 'Updated Colour',
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(404);
    }

    public function test_creating_a_category_with_invalid_colour_fails_validation(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();

        $response = $this->postJson("/api/merchants/{$merchant->id}/categories", [
            'name' => 'Starters',
            'colour' => 'MAUVE', // Invalid colour, assuming only certain colours are allowed
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('colour');
    }

    public function test_updating_a_category_with_invalid_colour_fails_validation(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $merchant = Merchant::factory()->create();
        $category = Category::factory()->for($merchant)->create([
            'name' => 'Starters',
            'colour' => 'RED',
        ]);

        $response = $this->patchJson("/api/merchants/{$merchant->id}/categories/{$category->id}", [
            'colour' => 'MAUVE', // Invalid colour, assuming only certain colours are allowed
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('colour');
    }
}
