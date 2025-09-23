<?php

namespace App\Policies;

use App\Models\Test;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
            return false;
    }

    public function view(User $user, Test $test): bool
    {
        return false;
    }

        {
        return true;ublic function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Test $test): bool
    {
        return false;
    }

    public function delete(User $user, Test $test): bool
    {
        return false;
    }

    public function restore(User $user, Test $test): bool
    {
        return false;
    }

    public function forceDelete(User $user, Test $test): bool
    {
        return false;
    }
}
