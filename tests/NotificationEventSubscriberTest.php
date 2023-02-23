<?php

use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\TestNotification;

it('will log a sent notification', function () {
    $user = User::factory()->create();

    $user->notify(new TestNotification());

    expect(NotificationLogItem::get())->toHaveCount(1);

    $logItem = NotificationLogItem::first();

    expect($logItem)
        ->notifiable_type->toBe(User::class)
        ->notifiable_id->toBe($user->getKey())
        ->notification_type->toBe(TestNotification::class)
        ->channel->toBe('mail');
});
