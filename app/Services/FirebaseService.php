<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    protected function getAccessToken(): string
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $credentials = new ServiceAccountCredentials(
            $scopes,
            json_decode(file_get_contents(config('firebase.credentials')), true)
        );

        $token = $credentials->fetchAuthToken();

        return $token['access_token'];
    }

    public function sendPush(array $payload)
    {
        $accessToken = $this->getAccessToken();

        $url = 'https://fcm.googleapis.com/v1/projects/'
            . config('firebase.project_id')
            . '/messages:send';

        return Http::withToken($accessToken)
            ->post($url, [
                'message' => $payload
            ])
            ->json();
    }
}
