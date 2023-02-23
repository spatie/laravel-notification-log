<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

class LogExtraNotification extends TestNotification
{
    public function logExtra(): array
    {
        return ['extraKey' => 'extraValue'];
    }
}
