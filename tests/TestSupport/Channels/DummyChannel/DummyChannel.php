<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Channels\DummyChannel;

use Illuminate\Notifications\Notification;

class DummyChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        $result = $notification->toDummyChannel();

        if ($result === 'throw-exception') {
            throw new DummyChannelException;
        }
    }
}
