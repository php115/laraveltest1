<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\Notification\NotificationSenderManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 2;

    public function __construct(public Notification $notification) {}

    public function handle(NotificationSenderManager $manager): void
    {
        if ($this->notification->status === Notification::STATUS_SENT) {
            return;
        }

        // Выбираем нужный драйвер и отправляем
        $manager->driver($this->notification->channel)->send($this->notification);

        $this->notification->update([
            'status' => Notification::STATUS_SENT
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->notification->update([
            'status' => Notification::STATUS_ERROR
        ]);
    }
}

