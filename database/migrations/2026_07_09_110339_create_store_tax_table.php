<?php

use App\Models\Store;
use App\Models\Tax;
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
        Schema::create('store_tax', function (Blueprint $table) {
            $table->foreignIdFor(Store::class)->constrained();
            $table->foreignIdFor(Tax::class)->constrained();
            $table->unique(['store_id', 'tax_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_tax');
    }
};
