<?php

use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Notifications\TestNotification;

it('will log a sent notification', function () {
    $user = User::factory()->create();

    $user->notify(new TestNotification());
});
