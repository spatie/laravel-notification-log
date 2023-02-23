<?php

use Illuminate\Support\Facades\Notification;
use Spatie\NotificationLog\Exceptions\InvalidExtraContent;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Channels\DummyChannel\DummyChannel;
use Spatie\NotificationLog\Tests\TestSupport\Channels\DummyChannel\DummyChannelException;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\ChannelWillThrowExceptionNotification;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\FingerprintNotification;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\InvalidLogExtraNotification;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\LogExtraNotification;
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

it('can log a all notifications sent to a collection of notifiables', function() {
   $users = User::factory()->count(5)->create();

   Notification::send($users, new TestNotification());

   expect(NotificationLogItem::get())->toHaveCount(5);
});

it('can log extra information', function() {
    Notification::send($this->user, new LogExtraNotification());

    $logItem = NotificationLogItem::first();

    expect($logItem->extra)->toBe(['extraKey' => 'extraValue']);
});

it('will throw an exception if the extra method returns something invalid', function() {
    Notification::send($this->user, new InvalidLogExtraNotification());
})->throws(InvalidExtraContent::class);

it('can handle an on-demand notification', function() {
    Notification::route('mail', 'john@example.com')->notify(new TestNotification());

    $logItem = NotificationLogItem::first();

    expect($logItem->anonymous_notifiable_properties)->toBe([
        'mail' => 'john@example.com',
    ]);
});

it('can handle a notification with a fingerprint', function() {
    $this->user->notify(new FingerprintNotification());

    $logItem = NotificationLogItem::first();

    expect($logItem->fingerprint)->toBe('this-is-a-fingerprint');
});

it('will log an unsent notification when there was a problem sending it', function() {
    try {
        $this->user->notify(new ChannelWillThrowExceptionNotification());

    } catch (DummyChannelException) {

    }

    $logItem = NotificationLogItem::first();

    expect($logItem->sent_at)->toBeNull();
});
