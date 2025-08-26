<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    public function view(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id &&
            $notification->notifiable_type === User::class;
    }

    public function markAsRead(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id &&
            $notification->notifiable_type === User::class;
    }

    public function delete(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id &&
            $notification->notifiable_type === User::class;
    }
}
