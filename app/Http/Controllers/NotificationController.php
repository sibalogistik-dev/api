<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->user()
            ->notifications()
            ->latest()
            ->get();

        return ApiResponseHelper::success('All Notifications', $data);
    }

    public function unread(Request $request)
    {
        $data = $request->user()->unreadNotifications;

        return ApiResponseHelper::success('Unread Notifications', $data);
    }

    public function unreadCount(Request $request)
    {
        $data = $request->user()->unreadNotifications->count();

        return ApiResponseHelper::success('Unread Notifications Count', $data);
    }

    public function markAsRead(Request $request, $id)
    {
        $data = $request->user()
            ->notifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return ApiResponseHelper::success('Notification marked as read', $data);
    }
}
