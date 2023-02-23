<?php

namespace Spatie\NotificationLog;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;

class NotificationEventSubscriber
{
    public function handleNotificationSending(NotificationSending $event): void
    {
        dd($event);
    }

    public function handleNotificationSent(NotificationSent $event): void
    {

    }

    public function subscribe(): array
    {
        return [
            NotificationSending::class => 'handleNotificationSending',
            NotificationSent::class => 'handleNotificationSent',
        ];
    }
}
