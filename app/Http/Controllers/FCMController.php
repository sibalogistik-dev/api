<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Faker\Factory;
use Illuminate\Http\Request;

class FCMController extends Controller
{
    public function test(FirebaseService $firebase)
    {
        $topic  = Factory::create()->word();
        $title  = 'Test ' . rand(1, 1000);
        return $firebase->sendPush([
            'topic' => $topic,
            'notification' => [
                'title' => $title,
                'body' => 'FCM from Laravel'
            ]
        ]);
    }
}
