<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

class CustomTypeNotification extends TestNotification
{
    public function logType(): string
    {
        return 'customType';
    }
}
