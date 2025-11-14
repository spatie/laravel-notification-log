<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Spatie\NotificationLog\Tests\TestSupport\CustomNotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\TestNotification;

class CustomNotificationLogItemFactory extends Factory
{
    public $model = CustomNotificationLogItem::class;

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

    public function forNotifiable(Model $model): Factory
    {
        return $this->state(function (array $attributes) use ($model) {
            return [
                'notifiable_type' => $model->getMorphClass(),
                'notifiable_id' => $model->getKey(),
                'notification_type' => $attributes['notification_type'] ?? TestNotification::class,
            ];
        });
    }
}
