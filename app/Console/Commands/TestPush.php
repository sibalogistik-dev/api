<?php

namespace App\Console\Commands;

use App\Services\FcmService;
use Illuminate\Console\Command;

class TestPush extends Command
{
    protected $signature = 'test:push {user_id} {app_name}';
    protected $description = 'Tes kirim notifikasi FCM';

    public function handle(FcmService $fcmService)
    {
        $userId = $this->argument('user_id');
        $appName = $this->argument('app_name');

        $this->info("Mengirim notifikasi ke User: $userId di App: $appName...");

        $result = $fcmService->sendNotification(
            $userId,
            $appName,
            "Halo Bro!",
            "Ini tes notifikasi pertama kamu!"
        );

        if ($result['success']) {
            $this->info("Berhasil! Terkirim ke {$result['sent']} perangkat.");
        } else {
            $this->error($result['message']);
        }
    }
}
