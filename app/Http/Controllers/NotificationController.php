<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json([
            'success' => true,
            'notifications' => $notifications->items(),
        ]);
    }

    public function reviewNotifications()
    {
        $user = auth()->user();
        $reviewNotifications = $user->notifications()
            ->where('type', 'App\\Notifications\\NewReview')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'notifications' => $reviewNotifications->items(),
        ]);
    }

    public function markAsRead($id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $this->authorize('markAsRead', $notification);
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }
}
