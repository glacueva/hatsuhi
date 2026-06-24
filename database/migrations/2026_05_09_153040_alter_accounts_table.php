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
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('is_shared')->default(false)->after('is_main');
            $table->double('share', 2, 2)->nullable()->after('is_shared')
                ->comment('Percentage of the account shared with other users, only applicable if is_shared is true')
                ->default(100)
                ->check('share >= 0 AND share <= 100');
        });

        Schema::table('movements', function (Blueprint $table) {
            $table->double('share', 2, 2)->nullable()->after('amount')
                ->comment('Percentage of the account when the movement was made, only applicable if the account is shared')
                ->default(100)
                ->check('share >= 0 AND share <= 100');
            $table->double('shared_amount', 2, 2)->nullable()->after('amount')
                ->comment('Percentage of the movement amount that belongs to the user, only applicable if the account is shared');
        });
        DB::statement('UPDATE movements SET shared_amount = amount WHERE shared_amount IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['is_shared', 'share']);
        });
        Schema::table('movements', function (Blueprint $table) {
            $table->dropColumn('share', 'shared_amount');
        });
    }
};
