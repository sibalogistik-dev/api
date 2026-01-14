<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewTrainingNotification extends Notification
{
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => $this->payload['title'],
            'message' => $this->payload['message'],
            'status'  => $this->payload['status'],
            'url'     => $this->payload['url'] ?? null,
        ];
    }
}
