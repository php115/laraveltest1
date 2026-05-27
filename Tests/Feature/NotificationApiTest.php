<?php

namespace Tests\Feature;

use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_notification_and_dispatches_job(): void
    {
        Queue::fake();

        $payload = [
            'user_id' => 42,
            'text' => 'Hello test message',
            'channel' => 'telegram',
        ];

        $response = $this->postJson('/api/notifications', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'processing')
            ->assertJsonPath('data.text', 'Hello test message');

        $this->assertDatabaseHas('notifications', [
            'user_id' => 42,
            'channel' => 'telegram'
        ]);

        Queue::assertDispatched(SendNotificationJob::class);
    }

    public function test_it_validates_max_length_of_text(): void
    {
        $payload = [
            'user_id' => 42,
            'text' => str_repeat('a', 501), // Больше 500
            'channel' => 'email',
        ];

        $response = $this->postJson('/api/notifications', $payload);
        $response->assertStatus(422);
    }
}

