<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getUserNotifications(Request $request)
    {
        // Retrieve notifications for the authenticated user, ordered by created_at
        $notifications = $request->user()->notifications()->orderBy('created_at', 'desc')->get();

        // Return the notifications as a JSON response
        return response()->json($notifications);
    }

    public function markNotificationAsRead($notificationId)
    {
        // Find the notification by its ID
        $notification = Notification::findOrFail($notificationId); // Use Notification model here

        // Mark it as read
        $notification->update(['is_read' => true]);

        // Return a response indicating success
        return response()->json(['message' => 'Notification marked as read.']);
    }
}
