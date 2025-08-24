<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->role == 'admin' || $user->role == 'instructor';
    }

    public function viewInstructors(User $user): bool
    {
        return $user->role == 'admin' || $user->role == 'instructor';
    }

    public function viewAdmins(User $user): bool
    {
        return $user->role == 'admin';
    }


    public function view(User $user, User $model): bool
    {
        return $user->role == 'admin' || ($user->role == 'instructor');
    }

    public function create(User $user): bool
    {
        return $user->role == 'admin';
    }

    public function update(User $user, User $model): bool
    {
        return $user->role == 'admin';
    }

    public function delete(User $user, User $model): bool
    {
         return $user->role == 'admin';
    }
}
