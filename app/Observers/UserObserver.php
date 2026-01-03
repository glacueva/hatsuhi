<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        // Create default types for the new user/tenant
        $income = $user->movementTypes()->create([
            'name' => 'General Income',
            'is_positive' => true,
            'tenant_id' => $user->id,
        ]);

        $expense = $user->movementTypes()->create([
            'name' => 'General Expense',
            'is_positive' => false,
            'tenant_id' => $user->id,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

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
