<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Euro currency (assuming EUR is the first one seeded)
        $euroCurrency = Currency::where('short', 'EUR')->first();

        if (! $euroCurrency) {
            // If EUR doesn't exist, use the first currency
            $euroCurrency = Currency::first();
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@hatsuhi.app',
            'password' => Hash::make('password'),
            'currency_id' => $euroCurrency->id,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@hatsuhi.app',
            'password' => Hash::make('password'),
            'currency_id' => $euroCurrency->id,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // Create another regular user with USD
        $usdCurrency = Currency::where('short', 'USD')->first();
        if ($usdCurrency) {
            User::create([
                'name' => 'US User',
                'email' => 'us.user@hatsuhi.app',
                'password' => Hash::make('password'),
                'currency_id' => $usdCurrency->id,
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@hatsuhi.app / password');
        $this->command->info('User: user@hatsuhi.app / password');
    }
}
