<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

use Illuminate\Notifications\Events\NotificationSending;

class ShouldNotLogNotification extends TestNotification
{
    public function shouldLog(NotificationSending $event): bool
    {
        return false;
    }
}
