<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Task $task): bool
    {
        return $user->role === 'admin' || $user->id === $task->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'instructor';
    }

    public function update(User $user, Task $task): bool
    {
        return $user->role === 'admin' || $user->id === $task->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->role === 'admin' || $user->id === $task->user_id;
    }

    public function restore(User $user, Task $task): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $user->role === 'admin';
    }
}
