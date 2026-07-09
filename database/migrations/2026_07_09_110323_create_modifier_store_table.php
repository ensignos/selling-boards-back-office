<?php

use App\Models\Modifier;
use App\Models\Store;
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
        Schema::create('modifier_store', function (Blueprint $table) {
            $table->foreignIdFor(Modifier::class)->constrained();
            $table->foreignIdFor(Store::class)->constrained();
            $table->unique(['modifier_id', 'store_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifier_store');
    }
};
