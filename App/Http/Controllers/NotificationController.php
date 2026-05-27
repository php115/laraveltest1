<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotificationController extends Controller
{
    public function store(CreateNotificationRequest $request): NotificationResource
    {
        $notification = Notification::create(array_merge(
            $request->validated(),
            ['status' => Notification::STATUS_PROCESSING]
        ));

        // Отправляем в очередь на обработку
        SendNotificationJob::dispatch($notification);

        return new NotificationResource($notification);
    }

    public function show(Notification $notification): NotificationResource
    {
        return new NotificationResource($notification);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'user_id' => 'required|integer',
            'status' => 'nullable|string|in:processing,sent,error',
            'channel' => 'nullable|string|in:email,telegram',
        ]);

        $query = Notification::query()->where('user_id', $request->integer('user_id'));

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->string('channel'));
        }

        return NotificationResource::collection($query->latest()->paginate(15));
    }
}

