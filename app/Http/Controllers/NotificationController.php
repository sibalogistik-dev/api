<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->notifications()
            ->latest()
            ->get();
    }

    public function unread(Request $request)
    {
        return $request->user()->unreadNotifications;
    }

    public function markAsRead(Request $request, $id)
    {
        $request->user()
            ->notifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked as read']);
    }
}
