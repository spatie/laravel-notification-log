<?php

use Spatie\NotificationLog\Models\NotificationLogItem;
use \Spatie\NotificationLog\Tests\TestSupport\Models\User;

beforeEach(function() {
    $this->user = User::factory()->create();

    $this->anotherUser = User::factory()->create();

});

it('can find the latest notification for a notifiable', function() {
    $firstLogItem = NotificationLogItem::factory()->forNotifiable($this->user)->create();

    $secondLogItem = NotificationLogItem::factory()->forNotifiable($this->user)->create();
    $otherUserLogItem = NotificationLogItem::factory()->forNotifiable($this->anotherUser)->create();

    $foundLogItem = NotificationLogItem::latestFor($this->user);

    expect($foundLogItem)->toBeModel($secondLogItem);

});
