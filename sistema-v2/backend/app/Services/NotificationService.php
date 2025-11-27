<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Create a new notification in the database.
     *
     * @param int $userId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @return Notification
     */
    public function create($userId, $type, $title, $message, $data = [])
    {
        $notification = Notification::create([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        // Emit real-time notification via broadcasting
        event(new \App\Events\NotificationSent($notification));

        return $notification;
    }

    /**
     * Send a notification (create in DB and optionally send email).
     *
     * @param int $userId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param bool $sendEmail
     * @return Notification
     */
    public function send($userId, $type, $title, $message, $data = [], $sendEmail = false)
    {
        $notification = $this->create($userId, $type, $title, $message, $data);

        // TODO: Send email if $sendEmail is true
        // This will be implemented when we create the mail classes

        return $notification;
    }

    /**
     * Mark a notification as read.
     *
     * @param string $notificationId
     * @return bool
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return false;
        }

        return $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for a user.
     *
     * @param int $userId
     * @return int
     */
    public function markAllAsRead($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->update(['read_at' => now()]);
    }

    /**
     * Delete a notification.
     *
     * @param string $notificationId
     * @return bool
     */
    public function delete($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return false;
        }

        return $notification->delete();
    }

    /**
     * Get user notifications (paginated).
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserNotifications($userId, $limit = 10)
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get unread notifications count for a user.
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->count();
    }
}
