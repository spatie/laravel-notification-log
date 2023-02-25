<?php

namespace Spatie\NotificationLog\Tests\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->anotherUser = User::factory()->create();

    $this->firstUser1Log = NotificationLogItem::factory()->forNotifiable($this->user)->create();
    $this->secondUser1Log = NotificationLogItem::factory()->forNotifiable($this->user)->create();
    $this->firstUser2Log = NotificationLogItem::factory()->forNotifiable($this->anotherUser)->create();
});

it('can get all notification log items for a notifiable', function () {
    expect($this->user->notificationLogItems())->toBeInstanceOf(MorphMany::class);

    expect($this->user->notificationLogItems)->toHaveCount(2);
    expect($this->anotherUser->notificationLogItems)->toHaveCount(1);
});

it('can get the latest notification for a user', function () {
    expect($this->user->latestLoggedNotification())->toBeModel($this->secondUser1Log);

    expect($this->anotherUser->latestLoggedNotification())->toBeModel($this->firstUser2Log);
});
