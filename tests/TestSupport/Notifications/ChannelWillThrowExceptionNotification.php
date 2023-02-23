<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

use Spatie\NotificationLog\Tests\TestSupport\Channels\DummyChannel\DummyChannel;

class ChannelWillThrowExceptionNotification extends TestNotification
{
    public function via(object $notifiable): array
    {
        return [DummyChannel::class];
    }

    public function toDummyChannel(): string
    {
        return 'throw-exception';
    }
}
