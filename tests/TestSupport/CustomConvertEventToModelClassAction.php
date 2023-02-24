<?php

namespace Spatie\NotificationLog\Tests\TestSupport;

use Illuminate\Notifications\Events\NotificationSending;
use Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItemAction;

class CustomConvertEventToModelClassAction extends ConvertNotificationSendingEventToLogItemAction
{
    protected function getExtra(NotificationSending $event): array
    {
        return ['customName' => 'customKey'];
    }
}
