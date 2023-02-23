<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomTypeNotification extends TestNotification
{
    public function logType(): string
    {
        return 'customType';
    }
}
