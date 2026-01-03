<?php

namespace App\Policies;

use App\Models\Expectation;
use App\Models\User;

class ExpectationPolicy
{
    // Admin can do anything with any Expectation
    // Users can only work with their own Expectations
    
    public function viewAny(User $user): bool
    {
        return true; // Both admins and users can view lists (filtered by their own data)
    }
    
    public function view(User $user, Expectation $expectation): bool
    {
        return $user->isAdmin() || $user->id === $expectation->user_id;
    }
    
    public function create(User $user): bool
    {
        return true; // Both can create
    }
    
    public function update(User $user, Expectation $expectation): bool
    {
        return $user->isAdmin() || $user->id === $expectation->user_id;
    }
    
    public function delete(User $user, Expectation $expectation): bool
    {
        return $user->isAdmin() || $user->id === $expectation->user_id;
    }
}