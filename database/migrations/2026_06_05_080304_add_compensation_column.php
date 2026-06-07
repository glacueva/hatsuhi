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
        //
        Schema::table('movements', function (Blueprint $table) {
            $table->boolean('is_compensation')->default(false)->after('shared_amount');
        });
        DB::table('movements')->where('amount', '<', 0)->update(['is_compensation' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('movements', function (Blueprint $table) {
            $table->dropColumn('is_compensation');
        });
    }
};
