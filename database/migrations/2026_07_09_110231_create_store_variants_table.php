<?php

use App\Models\Merchant;
use App\Models\Store;
use App\Models\Variant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Merchant::class)->constrained();
            $table->foreignIdFor(Variant::class)->constrained();
            $table->foreignIdFor(Store::class)->constrained();
            $table->unique(['variant_id', 'store_id']);
            $table->string('pricing_type', 50);
            $table->decimal('price', 20, 6);
            $table->boolean('available_for_sale');
            $table->string('optimal_stock', 50);
            $table->string('low_stock', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_variants');
    }
};
