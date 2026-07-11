<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class AccountPolicy
{
    // Admin can do anything with any Account
    // Users can only work with their own Accounts

    public function viewAny(User $user): bool
    {
        return true; // Both admins and users can view lists (filtered by their own data)
    }

    public function view(User $user, Account $account): bool
    {
        return $user->isAdmin() || $user->id === $account->user_id;
    }

    public function create(User $user): bool
    {
        return true; // Both can create
    }

    public function update(User $user, Account $account): bool
    {
        return $user->isAdmin() || $user->id === $account->user_id;
    }

    public function delete(User $user, Account $account): bool
    {
        return $user->isAdmin() || $user->id === $account->user_id;
    }
}
