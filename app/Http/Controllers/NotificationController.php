<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(): JsonResponse
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

    public function markAsRead($id): JsonResponse
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
