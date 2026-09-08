<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->get();

        return $this->success(
            NotificationResource::collection($notifications),
            'Notifications retrieved successfully.'
        );
    }

    public function unread(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->unreadNotifications()
            ->latest()
            ->get();

        return $this->success(
            NotificationResource::collection($notifications),
            'Unread notifications retrieved successfully.'
        );
    }

    public function markAsRead(
        Request $request,
        string $notification
    ): JsonResponse {
        $userNotification = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $userNotification->markAsRead();

        return $this->success(
            new NotificationResource($userNotification),
            'Notification marked as read successfully.'
        );
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return $this->success(
            null,
            'All notifications marked as read successfully.'
        );
    }

    public function destroy(
        Request $request,
        string $notification
    ): JsonResponse {
        $userNotification = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $userNotification->delete();

        return $this->success(
            null,
            'Notification deleted successfully.'
        );
    }
}