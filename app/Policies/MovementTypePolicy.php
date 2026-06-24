<?php

namespace App\Policies;

use App\Models\MovementType;
use App\Models\User;

class MovementTypePolicy
{
    // Admin can do anything with any movementType
    // Users can only work with their own movementTypes

    public function viewAny(User $user): bool
    {
        return true; // Both admins and users can view lists (filtered by their own data)
    }

    public function view(User $user, MovementType $movementType): bool
    {
        return $user->isAdmin() || $user->id === $movementType->user_id;
    }

    public function create(User $user): bool
    {
        return true; // Both can create
    }

    public function update(User $user, MovementType $movementType): bool
    {
        return $user->isAdmin() || $user->id === $movementType->user_id;
    }

    public function delete(User $user, MovementType $movementType): bool
    {
        return $user->isAdmin() || $user->id === $movementType->user_id;
    }
}
