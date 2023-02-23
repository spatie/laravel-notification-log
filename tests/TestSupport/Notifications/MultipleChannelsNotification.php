<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

use Spatie\NotificationLog\Tests\TestSupport\Channels\DummyChannel\DummyChannel;

class MultipleChannelsNotification extends TestNotification
{
    public function via(object $notifiable): array
    {
        return ['mail', DummyChannel::class];
    }

    public function toDummyChannel(): string
    {
        return 'dummy message';
    }
}
