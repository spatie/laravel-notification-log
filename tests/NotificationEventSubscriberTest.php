<?php

use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Channels\DummyChannel\DummyChannel;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\MultipleChannelsNotification;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\ShouldNotLogNotification;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\TestNotification;

beforeEach(function() {
   $this->user = User::factory()->create();
});

it('will log a sent notification', function () {
    $this->user->notify(new TestNotification());

    expect(NotificationLogItem::get())->toHaveCount(1);

    $logItem = NotificationLogItem::first();

    expect($logItem)
        ->notifiable_type->toBe($this->user::class)
        ->notifiable_id->toBe($this->user->getKey())
        ->notification_type->toBe(TestNotification::class)
        ->channel->toBe('mail')
        ->sent_at->not()->toBeNull();
});

it('will not log a notification that should not be logged', function() {
    $this->user->notify(new ShouldNotLogNotification());

    expect(NotificationLogItem::get())->toHaveCount(0);
});

it('can log a notification that is being sent to multiple channels', function() {
    $this->user->notify(new MultipleChannelsNotification());

    expect(NotificationLogItem::get())->toHaveCount(2);

    $logItems = NotificationLogItem::get();

    expect($logItems[0]->channel)->toBe('mail');
    expect($logItems[1]->channel)->toBe(DummyChannel::class);
});
