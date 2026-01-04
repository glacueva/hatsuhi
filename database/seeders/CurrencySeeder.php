<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'Euro',
                'short' => 'EUR',
                'symbol' => '€',
            ],
            [
                'name' => 'US Dollar',
                'short' => 'USD',
                'symbol' => '$',
            ],
            [
                'name' => 'British Pound',
                'short' => 'GBP',
                'symbol' => '£',
            ],
            [
                'name' => 'Japanese Yen',
                'short' => 'JPY',
                'symbol' => '¥',
            ],
            [
                'name' => 'Swiss Franc',
                'short' => 'CHF',
                'symbol' => 'CHF',
            ],
            [
                'name' => 'Canadian Dollar',
                'short' => 'CAD',
                'symbol' => 'CA$',
            ],
            [
                'name' => 'Australian Dollar',
                'short' => 'AUD',
                'symbol' => 'A$',
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }

        $this->command->info('Currencies seeded successfully!');
    }
}
