<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $allowedDevices = [
        'hrd',
        'employee',
        'finance',
        'marketing',
        'operational',
        'super',
    ];

    public function sendFirebaseNotification($userId, $appName, $title, $body, $imageUrl = null)
    {
        $fcmTokenController = new FcmTokenController();
        return $fcmTokenController->sendPushToSpecificApp($userId, $appName, $title, $body, $imageUrl);
    }
}
