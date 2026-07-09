<?php

use App\Models\Category;
use App\Models\Merchant;
use App\Models\Supplier;
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Merchant::class)->constrained();
            $table->string('handle', 100);
            $table->string('name', 50);
            $table->text('description');
            $table->boolean('track_stock');
            $table->boolean('sold_by_weight');
            $table->boolean('is_composite');
            $table->boolean('use_production');
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Supplier::class);
            $table->string('form', 10);
            $table->string('colour', 10);
            $table->string('image_url');
            $table->string('option1_name', 100);
            $table->string('option2_name', 100);
            $table->string('option3_name', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
