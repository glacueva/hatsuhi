<?php

namespace App\Policies;

use App\Models\Movement;
use App\Models\User;

class MovementPolicy
{
    // Admin can do anything with any movement
    // Users can only work with their own movements

    public function viewAny(User $user): bool
    {
        return true; // Both admins and users can view lists (filtered by their own data)
    }

    public function view(User $user, Movement $movement): bool
    {
        return $user->isAdmin() || $user->id === $movement->user_id;
    }

    public function create(User $user): bool
    {
        return true; // Both can create
    }

    public function update(User $user, Movement $movement): bool
    {
        return $user->isAdmin() || $user->id === $movement->user_id;
    }

    public function delete(User $user, Movement $movement): bool
    {
        return $user->isAdmin() || $user->id === $movement->user_id;
    }
}
