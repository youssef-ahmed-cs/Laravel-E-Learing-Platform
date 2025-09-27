<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->bio = Str::limit($user->bio, 70);
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->phone = "+20{$user->phone}";
        $user->avatar =  url('/storage/' . $user->avatar);
    }

    public function updated(User $user): void
    {
        $user->bio = Str::limit($user->bio, 70);
        if ($user->isDirty('login_count')) {
            Log::info("User login: {$user->email} - Count: {$user->login_count}");
        }
    }

    public function deleted(User $user): void
    {
        Log::warning("User deleted", [
            'user_id' => $user->id,
            'email' => $user->email,
            'deleted_at' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Log::info('User restored: ' . $user->email);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Log::debug('User force deleted: ' . $user->email);
    }
}
