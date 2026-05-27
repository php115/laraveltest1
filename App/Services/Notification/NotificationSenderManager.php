<?php

namespace App\Services\Notification;

use App\Services\Notification\Channels\EmailSender;
use App\Services\Notification\Channels\TelegramSender;
use Illuminate\Support\Manager;

class NotificationSenderManager extends Manager
{
    public function getDefaultDriver(): string
    {
        throw new \InvalidArgumentException('No default notification channel defined.');
    }

    public function createEmailDriver(): EmailSender
    {
        return new EmailSender();
    }

    public function createTelegramDriver(): TelegramSender
    {
        return new TelegramSender();
    }
}

