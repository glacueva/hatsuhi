<?php

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
        Schema::create('expectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('movement_category_id')->constrained();
            $table->year('year');
            $table->decimal('amount', 15, 2);
            // Unique constraint now includes tenant_id
            $table->unique(['user_id', 'movement_category_id', 'year'], 'tenant_category_year_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expectations');
    }
};
