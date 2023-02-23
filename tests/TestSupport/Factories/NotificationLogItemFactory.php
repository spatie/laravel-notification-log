<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\TestNotification;

class NotificationLogItemFactory extends Factory
{
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'notification_type' => TestNotification::class,
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->getKey(),
            'channel' => 'mail',
        ];
    }
}
