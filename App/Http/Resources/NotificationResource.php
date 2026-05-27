<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'text' => $this->text,
            'status' => $this->status,
            'channel' => $this->channel,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

