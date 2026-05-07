<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops the movement_alarms table and its references if they exist.
     * This migration is safe to run even if the alarms feature was never implemented.
     */
    public function up(): void
    {
        // Drop the movement_alarms table if it exists
        Schema::dropIfExists('movement_alarms');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the movement_alarms table if needed for rollback
        Schema::create('movement_alarms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movement_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('alarm_date');
            $table->string('notification_type')->default('email');
            $table->boolean('is_sent')->default(false);
            $table->timestamps();

            $table->foreign('movement_id')->references('id')->on('movements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
