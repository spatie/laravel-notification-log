<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->anotherUser = User::factory()->create();
});

it('can find the latest notification for a notifiable', function () {
    expect(NotificationLogItem::latestFor($this->user))->toBeNull();

    $firstLogItem = NotificationLogItem::factory()->forNotifiable($this->user)->create();

    $secondLogItem = NotificationLogItem::factory()->forNotifiable($this->user)->create();
    $otherUserLogItem = NotificationLogItem::factory()->forNotifiable($this->anotherUser)->create();

    expect(NotificationLogItem::latestFor($this->user))->toBeModel($secondLogItem);
    expect(NotificationLogItem::latestFor($this->anotherUser))->toBeModel($otherUserLogItem);
});

it('can find the latest sent notification for a type', function () {
    $firstType1 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'notification_type' => 'type1',
    ]);

    $secondType1 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'notification_type' => 'type1',
    ]);

    $firstType2 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'notification_type' => 'type2',
    ]);

    $secondType2 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'notification_type' => 'type2',
    ]);

    expect(NotificationLogItem::latestFor($this->user, notificationType: 'type1'))
        ->toBeModel($secondType1);

    expect(NotificationLogItem::latestFor($this->user, notificationType: 'type2'))
        ->toBeModel($secondType2);

    expect(NotificationLogItem::latestFor($this->user, notificationType: 'type3'))
        ->toBeNull();
});

it('can find the latest sent notification for fingerprint', function () {
    $firstFingerprint1 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'fingerprint' => 'fingerprint-1',
    ]);

    $secondFingerprint1 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'fingerprint' => 'fingerprint-1',
    ]);

    $firstFingerprint2 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'fingerprint' => 'fingerprint-2',
    ]);

    $secondFingerprint2 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'fingerprint' => 'fingerprint-2',
    ]);

    expect(NotificationLogItem::latestFor($this->user, fingerprint: 'fingerprint-1'))
        ->toBeModel($secondFingerprint1);

    expect(NotificationLogItem::latestFor($this->user, fingerprint: 'fingerprint-2'))
        ->toBeModel($secondFingerprint2);

    expect(NotificationLogItem::latestFor($this->user, fingerprint: 'fingerprint-3'))
        ->toBeNull();
});

it('can find the latest sent notification for channel', function () {
    $firstChannel1 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'channel' => 'channel-1',
    ]);

    $secondChannel1 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'channel' => 'channel-1',
    ]);

    $firstChannel2 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'channel' => 'channel-2',
    ]);

    $secondChannel2 = NotificationLogItem::factory()->forNotifiable($this->user)->create([
        'channel' => 'channel-2',
    ]);

    expect(NotificationLogItem::latestFor($this->user, channel: 'channel-1'))
        ->toBeModel($secondChannel1);

    expect(NotificationLogItem::latestFor($this->user, channel: 'channel-2'))
        ->toBeModel($secondChannel2);

    expect(NotificationLogItem::latestFor($this->user, channel: 'channel-3'))
        ->toBeNull();
});

it('can find the latest notification in a certain period', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->state(new Sequence(
            ['created_at' => '2023-01-01 00:00:00'],
            ['created_at' => '2023-01-02 00:00:00'],
            ['created_at' => '2023-01-03 00:00:00'],
            ['created_at' => '2023-01-04 00:00:00'],
            ['created_at' => '2023-01-05 00:00:00'],
            ['created_at' => '2023-01-06 00:00:00'],
            ['created_at' => '2023-01-07 00:00:00'],
        ))
        ->count(7)
        ->create();

    expect(NotificationLogItem::latestFor(
        $this->user,
        before: createCarbon('2023-01-04'))
    )
        ->toHaveCreationDate('2023-01-03');

    expect(NotificationLogItem::latestFor(
        $this->user,
        after: createCarbon('2023-01-04'))
    )
        ->toHaveCreationDate('2023-01-07');

    expect(NotificationLogItem::latestFor(
        $this->user,
        before: createCarbon('2023-01-06'),
        after: createCarbon('2023-01-03'))
    )
        ->toHaveCreationDate('2023-01-05');
});

function createCarbon($date): Carbon
{
    return Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
}
