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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->decimal('balance', 15, 2)->default(0);
            $table->boolean('is_main')->default(true); // only for having it selected by default when creating movements
            $table->timestamps();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('movements', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable()->after('user_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
        });

        // Create main account for each user
        $users = \DB::table('users')->get();
        foreach ($users as $user) {
            \DB::table('accounts')->insert([
                'user_id' => $user->id,
                'name' => 'Main Account',
                'balance' => 0,
                'is_main' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $accounts = \DB::table('accounts')->get();
        foreach ($accounts as $account) {
            \DB::table('movements')->where('user_id', $account->user_id)
                ->update(['account_id' => $account->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('movements', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });
        Schema::dropIfExists('accounts');
    }
};
