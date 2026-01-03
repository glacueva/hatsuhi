<?php

namespace App\Policies;

use App\Models\MovementCategory;
use App\Models\User;

class MovementCategoryPolicy
{
    // Admin can do anything with any movementCategory
    // Users can only work with their own movementCategorys
    
    public function viewAny(User $user): bool
    {
        return true; // Both admins and users can view lists (filtered by their own data)
    }
    
    public function view(User $user, MovementCategory $movementCategory): bool
    {
        return $user->isAdmin() || $user->id === $movementCategory->user_id;
    }
    
    public function create(User $user): bool
    {
        return true; // Both can create
    }
    
    public function update(User $user, MovementCategory $movementCategory): bool
    {
        return $user->isAdmin() || $user->id === $movementCategory->user_id;
    }
    
    public function delete(User $user, MovementCategory $movementCategory): bool
    {
        return $user->isAdmin() || $user->id === $movementCategory->user_id;
    }
}