<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'instructor';
    }

    public function view(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id
            || $user->role === 'admin'
            || $user->role === 'instructor';
    }

    public function delete(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->role === 'admin';
    }

    public function restore(User $user, Profile $profile): bool
    {
        return $user->role === 'admin' || $user->id === $profile->user_id;
    }
}
