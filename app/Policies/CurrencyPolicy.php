<?php

namespace App\Policies;

use App\Models\Currency;
use App\Models\User;

class CurrencyPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, Currency $c)
    {
        return $user->is_admin;
    }

    public function delete(User $user, Currency $c)
    {
        return $user->is_admin;
    }
}
