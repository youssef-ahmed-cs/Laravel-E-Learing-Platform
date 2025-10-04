<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function view(User $user, Category $category)
    {
        return true;
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Category $category)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Category $category)
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->role === 'admin';
    }

    public function getAllTrashed(User $user): bool
    {
        return $user->role === 'admin';
    }
}
