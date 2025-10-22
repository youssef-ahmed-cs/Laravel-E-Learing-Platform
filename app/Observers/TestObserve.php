<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TestObserve
{
    public function creating(User $user): void
    {
        $user->name = strtoupper($user->name);
        $user->email = strtolower($user->email);
        $user->password = Hash::make($user->password);
    }

    public function created(User $user): void
    {
        Log::info('New user created', ['id' => $user->id, 'email' => $user->email]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void {}

    public function saving(User $user): void
    {
        $user->name = strtoupper($user->name);
        $user->email = strtolower($user->email);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
