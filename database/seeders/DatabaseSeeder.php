<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run CurrencySeeder first (for foreign key constraint)
        $this->call(CurrencySeeder::class);
        
        // Then run UserSeeder
        $this->call(UserSeeder::class);
        
        // You can add more seeders here as needed
    }
}