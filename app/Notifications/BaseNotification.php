<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    use Queueable;

    abstract protected function getModuleName(): string;

    abstract protected function getTitle(): string;

    abstract protected function getMessage(): string;

    abstract protected function getNotificationData();

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return array_merge([
            'module' => $this->getModuleName(),
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
        ], $this->getNotificationData());
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
