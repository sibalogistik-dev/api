<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Karyawan;
use App\Models\User;
use App\Notifications\Employee\AttendanceReminder;
use Carbon\Carbon;
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

    public function attendanceReminder(Request $request)
    {
        $today = $request->input('date') ?? Carbon::today()->toDateString();
        $data = Karyawan::query()
            ->whereDoesntHave('attendance', function ($q) use ($today) {
                $q->where('date', $today);
            })
            ->get();

        foreach ($data as $karyawan) {
            $user = $karyawan->user;
            $this->notifyAttendaceReminder(
                $user,
                'warning',
                'Pengingat Absensi',
                'Jangan lupa untuk mengisi absensi hari ini.',
                '/absensi'
            );
        }

        return ApiResponseHelper::success('Attendance reminders sent successfully');
    }

    public function notifyAttendaceReminder(
        User $user,
        string $status,
        string $title,
        string $message,
        ?string $url = null
    ) {
        $user->notify(new AttendanceReminder([
            'title'   => $title,
            'message' => $message,
            'status'  => $status,
            'url'     => $url,
        ]));
    }
}
