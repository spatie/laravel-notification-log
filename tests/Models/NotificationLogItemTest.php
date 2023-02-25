<?php

use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->anotherUser = User::factory()->create();
});

it('can find the latest notification for a notifiable', function () {
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
});
