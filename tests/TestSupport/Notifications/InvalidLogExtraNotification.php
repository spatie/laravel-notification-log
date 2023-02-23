<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

class InvalidLogExtraNotification extends TestNotification
{
    public function logExtra()
    {
        return $this;
    }
}
