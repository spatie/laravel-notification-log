<?php

namespace Spatie\NotificationLog\Tests\TestSupport;

use Illuminate\Notifications\Events\NotificationSending;
use Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItem;

class CustomConvertEventToModelClass extends ConvertNotificationSendingEventToLogItem
{
    protected function getExtra(NotificationSending $event): array
    {
        return ['customName' => 'customKey'];
    }
}
