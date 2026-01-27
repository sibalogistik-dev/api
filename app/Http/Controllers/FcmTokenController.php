<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FcmTokenController extends Controller
{
    public function sendPushToSpecificApp($userId, $appName, $title, $body, $imageUrl = null)
    {
        $tokens = FcmToken::where('user_id', $userId)
            ->where('app_name', $appName)
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) return "Token tidak ditemukan untuk aplikasi ini.";

        $messaging = Firebase::messaging();
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body, $imageUrl));

        $report = $messaging->sendMulticast($message, $tokens);

        return "Terkirim ke " . $report->successes()->count() . " perangkat di aplikasi " . $appName;
    }

    public function store(Request $request)
    {
        $request->validate([
            'fcm_token'   => 'required',
            'device_type' => 'required',
            'app_name'    => 'required'
        ]);

        FcmToken::updateOrCreate(
            [
                'fcm_token' => $request->fcm_token,
            ],
            [
                'user_id'     => $request->user()->id,
                'device_type' => $request->device_type,
                'app_name'    => $request->app_name,
            ]
        );

        return response()->json(['message' => 'Token saved successfully']);
    }
}
