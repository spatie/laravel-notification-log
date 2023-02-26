<?php

use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\HasHistoryNotification;
use Spatie\TestTime\TestTime;

beforeEach(function () {
    TestTime::freeze();

    $this->notifiable = User::factory()->create();
});

it('can determine if it was sent in the past hour', function (
    int $createdMinutesAgo,
    bool $expectedResult,
) {
    NotificationLogItem::factory()
        ->forNotifiable($this->notifiable)
        ->create([
            'notification_type' => HasHistoryNotification::class,
            'created_at' => now()->subMinutes($createdMinutesAgo),
        ]);

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeInNotification($hasHistoryCalls, $this->notifiable))
        ->toBe($expectedResult);
})->with([
    [59, true],
    [60, true],
    [61, false],
]);

it('will return false when using it for an other notifiable', function () {
    $otherNotifiable = User::factory()->create();

    NotificationLogItem::factory()
        ->forNotifiable($otherNotifiable)
        ->create([
            'notification_type' => HasHistoryNotification::class,
            'created_at' => now()->subMinutes(30),
        ]);

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeInNotification($hasHistoryCalls, $this->notifiable))
        ->toBeFalse();
});

it('will return false when using it for an other notification type', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->notifiable)
        ->create([
            'notification_type' => 'other-type',
            'created_at' => now()->subMinutes(30),
        ]);

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeInNotification($hasHistoryCalls, $this->notifiable))
        ->toBeFalse();
});

it('can find a sent notification with the same fingerprint', function(
    ?string $fingerprint,
    bool $expectedResult,
) {
    NotificationLogItem::factory()
        ->forNotifiable($this->notifiable)
        ->create([
            'fingerprint' => $fingerprint,
            'notification_type' => HasHistoryNotification::class,
            'created_at' => now()->subMinutes(30),
        ]);

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasSentTo($notifiable, withSameFingerprint: true)
            ->inThePastMinutes(60);
    };

    expect(executeInNotification($hasHistoryCalls, $this->notifiable))
        ->toBe($expectedResult);
})->with([
    ['my-fingerprint', true],
    ['other-fingerprint', false],
    [null, false],
]);

it('can find a sent notification while ignoring the fingerprint', function(
    ?string $fingerprint,
) {
    NotificationLogItem::factory()
        ->forNotifiable($this->notifiable)
        ->create([
            'fingerprint' => $fingerprint,
            'notification_type' => HasHistoryNotification::class,
            'created_at' => now()->subMinutes(30),
        ]);

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeInNotification($hasHistoryCalls, $this->notifiable))
        ->toBeTrue();
})->with([
    ['my-fingerprint'],
    ['other-fingerprint'],
    [null],
]);

function executeInNotification(Closure $closure, User $notifiable): bool
{
    $closure = Closure::bind($closure, new HasHistoryNotification());

    HasHistoryNotification::setHistoryTestCallable($closure);

    return (new HasHistoryNotification())->historyTest($notifiable);
}
