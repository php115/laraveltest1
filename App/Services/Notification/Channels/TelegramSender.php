<?php

namespace App\Services\Notification\Channels;

use App\Models\Notification;
use App\Services\Notification\Contracts\NotificationSenderInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class TelegramSender implements NotificationSenderInterface
{
    public function send(Notification $notification): void
    {
        // Имитируем случайный сбой сети (10% вероятность) для проверки гарантии доставки
        if (app()->environment('production', 'local') && rand(1, 10) === 1) {
            throw new Exception("Telegram API limit exceeded for notification #{$notification->id}");
        }

        Log::info("Telegram message sent to user {$notification->user_id}");
    }
}

