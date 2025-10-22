<?php

namespace App\Policies;

use App\Enums\User as Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === Role::ADMIN->value ? true : null;
    }

    public function viewAny(User $user): Response
    {
        return ($user->role === Role::ADMIN->value || $user->role === Role::INSTRUCTOR->value) ? Response::allow() :
            Response::deny('You are not authorized');
    }

    public function viewInstructors(User $user): Response
    {
        return $user->role === Role::ADMIN->value || $user->role === Role::INSTRUCTOR->value ? Response::allow() :
            Response::deny('You are not authorized');
    }

    public function viewAdmins(User $user): bool
    {
        return $user->role === Role::ADMIN->value;
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === Role::ADMIN->value || ($user->id === $model->id);
    }

    public function create(User $user): bool
    {
        return $user->role === Role::ADMIN->value;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === Role::ADMIN->value;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === Role::ADMIN->value || $user->id === $model->id;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === Role::ADMIN->value;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->role === Role::ADMIN->value && $model->trashed();
    }
}
