<?php

namespace App\Services;

use App\Models\FcmToken;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public function sendNotification($userId, $appName, $title, $body, $data = [])
    {
        $tokens = FcmToken::where('user_id', $userId)
            ->where('app_name', $appName)
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            return ['success' => false, 'message' => 'Tidak ada token ditemukan.'];
        }

        $messaging = Firebase::messaging();

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $report = $messaging->sendMulticast($message, $tokens);

        if ($report->hasFailures()) {
            $invalidTokens = $report->invalidTokens();
            FcmToken::whereIn('fcm_token', $invalidTokens)->delete();
        }

        return [
            'success'   => true,
            'sent'      => $report->successes()->count(),
            'failed'    => $report->failures()->count(),
        ];
    }
}
