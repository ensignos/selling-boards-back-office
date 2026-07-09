<?php

use App\Models\Merchant;
use App\Models\Modifier;
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
        Schema::create('modifier_options', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Merchant::class)->constrained();
            $table->foreignIdFor(Modifier::class)->constrained();
            $table->string('name', 50);
            $table->decimal('price', 20, 6);
            $table->tinyInteger('position');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifier_options');
    }
};
