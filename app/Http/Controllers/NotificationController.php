<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function hrdIndex(Request $request)
    {
        $data = $request->user()
            ->notifications()
            ->where('data->module', 'hrd')
            ->latest()
            ->get();

        return ApiResponseHelper::success('All Notifications', $data);
    }

    public function hrdUnread(Request $request)
    {
        $data = $request->user()
            ->unreadNotifications()
            ->where('data->module', 'hrd')
            ->latest()
            ->get();

        return ApiResponseHelper::success('Unread Notifications', $data);
    }

    public function hrdUnreadCount(Request $request)
    {
        $data = $request->user()
            ->unreadNotifications()
            ->where('data->module', 'hrd')
            ->count();

        return ApiResponseHelper::success('Unread Notifications Count', $data);
    }

    public function employeeIndex(Request $request)
    {
        $data = $request->user()
            ->notifications()
            ->where('data->module', 'employee')
            ->latest()
            ->get();

        return ApiResponseHelper::success('All Notifications', $data);
    }

    public function employeeUnread(Request $request)
    {
        $data = $request->user()
            ->unreadNotifications()
            ->where('data->module', 'employee')
            ->latest()
            ->get();

        return ApiResponseHelper::success('Unread Notifications', $data);
    }

    public function employeeUnreadCount(Request $request)
    {
        $data = $request->user()
            ->unreadNotifications()
            ->where('data->module', 'employee')
            ->count();

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
