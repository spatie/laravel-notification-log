<?php

use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\HasHistoryNotification;
use Spatie\TestTime\TestTime;

beforeEach(function () {
    TestTime::freeze();

    $this->notifiable = User::factory()->create();
});

it('can determine if it was not sent in the past hour', function (
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
            ->wasNotSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeClosureInNotification($hasHistoryCalls, $this->notifiable))
        ->toBe($expectedResult);
})->with([
    [59, false],
    [60, false],
    [61, true],
]);

it('will return true when using it for an other notifiable', function () {
    $otherNotifiable = User::factory()->create();

    NotificationLogItem::factory()
        ->forNotifiable($otherNotifiable)
        ->create([
            'notification_type' => HasHistoryNotification::class,
            'created_at' => now()->subMinutes(30),
        ]);

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasNotSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeClosureInNotification($hasHistoryCalls, $this->notifiable))
        ->toBeTrue();
});

it('will return true when using it for an other notification type', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->notifiable)
        ->create([
            'notification_type' => 'other-type',
            'created_at' => now()->subMinutes(30),
        ]);

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasNotSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeClosureInNotification($hasHistoryCalls, $this->notifiable))
        ->toBeTrue();
});

it('can will not find a sent notification with the same fingerprint', function (
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
            ->wasNotSentTo($notifiable, withSameFingerprint: true)
            ->inThePastMinutes(60);
    };

    expect(executeClosureInNotification($hasHistoryCalls, $this->notifiable))
        ->toBe($expectedResult);
})->with([
    ['my-fingerprint', false],
    ['other-fingerprint', true],
    [null, true],
]);

it('will not find a sent notification while ignoring the fingerprint', function (
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
            ->wasNotSentTo($notifiable)
            ->inThePastMinutes(60);
    };

    expect(executeClosureInNotification($hasHistoryCalls, $this->notifiable))
        ->toBeFalse();
})->with([
    ['my-fingerprint'],
    ['other-fingerprint'],
    [null],
]);

it('can determine if it was not sent in the past', function (
    bool $created,
    bool $expectedResult,
) {
    if ($created) {
        NotificationLogItem::factory()
            ->forNotifiable($this->notifiable)
            ->create([
                'notification_type' => HasHistoryNotification::class,
                'created_at' => now(),
            ]);
    }

    $hasHistoryCalls = function ($notifiable) {
        return $this
            ->wasNotSentTo($notifiable)
            ->inThePast();
    };

    expect(executeClosureInNotification($hasHistoryCalls, $this->notifiable))
        ->toBe($expectedResult);
})->with([
    [true, false],
    [false, true],
]);

function executeClosureInNotification(Closure $closure, User $notifiable): bool
{
    $closure = Closure::bind($closure, new HasHistoryNotification());

    HasHistoryNotification::setHistoryTestCallable($closure);

    return (new HasHistoryNotification())->historyTest($notifiable);
}
