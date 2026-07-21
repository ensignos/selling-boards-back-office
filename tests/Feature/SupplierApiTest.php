<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_suppliers(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $suppliers = Supplier::factory()->count(3)->create();

        $response = $this->getJson("/api/suppliers", [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');

        foreach ($suppliers as $supplier) {
            $response->assertJsonFragment([
                'id' => $supplier->id
            ]);
        }
    }

    public function test_authenticated_user_can_create_a_supplier(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->postJson("/api/suppliers", [
            'name' => "S. Upplier"
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'S. Upplier'
        ]);
    }
}   
