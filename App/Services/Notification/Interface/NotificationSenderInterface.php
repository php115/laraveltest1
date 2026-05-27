<?php

namespace App\Services\Notification\Interface;

use App\Models\Notification;

interface NotificationSenderInterface
{
    /**
     * @throws \Exception Если отправка не удалась (для триггера retry в очереди)
     */
    public function send(Notification $notification): void;
}

