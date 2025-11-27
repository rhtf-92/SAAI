<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get user notifications (paginated).
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $limit = $request->input('limit', 10);

        $notifications = $this->notificationService->getUserNotifications($userId, $limit);

        return NotificationResource::collection($notifications);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount(Request $request)
    {
        $userId = $request->user()->id;
        $count = $this->notificationService->getUnreadCount($userId);

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = \App\Models\Notification::find($id);

        if (!$notification || $notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $this->notificationService->markAsRead($id);

        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->user()->id;
        $count = $this->notificationService->markAllAsRead($userId);

        return response()->json([
            'message' => 'All notifications marked as read',
            'count' => $count
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, $id)
    {
        $notification = \App\Models\Notification::find($id);

        if (!$notification || $notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $this->notificationService->delete($id);

        return response()->json(['message' => 'Notification deleted']);
    }
}
