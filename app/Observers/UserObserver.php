<?php

namespace App\Observers;

use App\Enums\Locale;
use App\Models\Currency;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        $user->update([
            'currency_id' => Currency::first()?->id,
            'locale' => $user->locale ?? Locale::EN,
        ]);

        // Create default types for the new user/tenant
        $income = $user->movementTypes()->create([
            'name' => __('app.hatsuhi.income', [], $user->locale->value),
            'is_positive' => true,
        ]);

        $expense = $user->movementTypes()->create([
            'name' => __('app.hatsuhi.expense', [], $user->locale->value),
            'is_positive' => false,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void {}

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
