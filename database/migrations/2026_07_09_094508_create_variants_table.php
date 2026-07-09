<?php

use App\Models\Item;
use App\Models\Merchant;
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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class)->constrained();
            $table->foreignIdFor(Merchant::class)->constrained();
            $table->string('sku', 50);
            $table->string('option1_value', 50);
            $table->string('option2_value', 50);
            $table->string('option3_value', 50);
            $table->string('barcode', 50);
            $table->decimal('cost', 20, 3);
            $table->decimal('purchase_cost', 20, 3);
            $table->string('default_pricing_type', 10);
            $table->decimal('default_price', 20, 3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
