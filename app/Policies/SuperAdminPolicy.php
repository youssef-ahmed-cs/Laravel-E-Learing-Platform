<?php

namespace App\Policies;

use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuperAdminPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {}

    public function view(User $user, SuperAdmin $superAdmin): bool {}

    public function create(User $user): bool {}

    public function update(User $user, SuperAdmin $superAdmin): bool {}

    public function delete(User $user, SuperAdmin $superAdmin): bool {}

    public function restore(User $user, SuperAdmin $superAdmin): bool {}

    public function forceDelete(User $user, SuperAdmin $superAdmin): bool {}
}
