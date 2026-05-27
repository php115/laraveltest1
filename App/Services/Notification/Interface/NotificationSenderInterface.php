<?php

namespace App\Services\Notification\Interface;

use App\Models\Notification;

interface NotificationSenderInterface
{
    public function send(Notification $notification): void;
}

