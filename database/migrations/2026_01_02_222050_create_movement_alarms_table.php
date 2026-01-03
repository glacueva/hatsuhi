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
        Schema::create('movement_alarms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('movement_category_id')->constrained();
            $table->date('date');
            $table->string('concept');
            $table->decimal('amount', 15, 2);
            $table->boolean('is_repeatable');
            $table->integer('periodicity_times')->nullable();
            $table->enum('periodicity_unit', ['year', 'month', 'day'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_alarms');
    }
};
