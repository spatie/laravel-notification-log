<?php

namespace Spatie\NotificationLog;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Spatie\NotificationLog\Models\NotificationLogItem;

class NotificationEventSubscriber
{
    public function handleNotificationSending(NotificationSending $event): void
    {
        if ( ! $this->shouldLog($event)) {
            return;
        }

        /** @var \Illuminate\Notifications\Notifiable $notifiable */
        $notifiable= $event->notifiable;

        NotificationLogItem::create([
            'notification_type' => get_class($event->notification),
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id'  => $notifiable->id,
            'channel' => $event->channel,
            'extra' => [],
        ]);
    }

    public function handleNotificationSent(NotificationSent $event): void
    {
        if ( ! $this->shouldLog($event)) {
            return;
        }
    }

    protected function shouldLog(NotificationSending|NotificationSent $event): bool
    {
        return true;
    }

    public function subscribe(): array
    {
        return [
            NotificationSending::class => 'handleNotificationSending',
            NotificationSent::class => 'handleNotificationSent',
        ];
    }
}
