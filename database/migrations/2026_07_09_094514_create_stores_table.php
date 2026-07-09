<?php

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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Merchant::class)->constrained();
            $table->string('name', 100);
            $table->string('address')->nullable()->default(null);
            $table->string('city', 100)->nullable()->default(null);
            $table->string('region', 100)->nullable()->default(null);
            $table->string('postal_code', 50)->nullable()->default(null);
            $table->string('country_code', 50)->nullable()->default(null);
            $table->string('phone_number', 50)->nullable()->default(null);
            $table->string('domain_url', 100)->nullable()->default(null);
            $table->geography('lat_lng_point', 'point')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
