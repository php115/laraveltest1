<?php

namespace App\Providers;

use App\Services\Notification\NotificationSenderManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NotificationSenderManager::class, function ($app) {
            return new NotificationSenderManager($app);
        });
    }
}

