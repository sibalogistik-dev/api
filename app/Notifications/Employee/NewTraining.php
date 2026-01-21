<?php

namespace App\Notifications\Employee;

use App\Notifications\BaseNotification;

class NewTraining extends BaseNotification
{
    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    protected function getModuleName(): string
    {
        return 'karyawan';
    }

    protected function getTitle(): string
    {
        return $this->details['title'];
    }

    protected function getMessage(): string
    {
        return $this->details['message'];
    }

    protected function getNotificationData(): array
    {
        return [
            'url'     => $this->details['url'] ?? null
        ];
    }
}
