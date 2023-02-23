<?php

namespace Spatie\NotificationLog;

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Spatie\NotificationLog\Support\Config;
use WeakMap;

class NotificationEventSubscriber
{
    protected static ?WeakMap $sentNotifications = null;

    public function __construct()
    {
        if (! self::$sentNotifications) {
            self::$sentNotifications = new WeakMap();
        }
    }

    public function handleNotificationSending(NotificationSending $event): void
    {
        if (! $this->shouldLog($event)) {
            return;
        }

        $convertEventToModelAction = Config::convertEventToModelAction();

        $logItem = $convertEventToModelAction->execute($event);

        if ($logItem) {
            self::$sentNotifications[$event->notification] = $logItem;
        }

        self::$sentNotifications[$event->notification] = $logItem;
    }

    public function handleNotificationSent(NotificationSent $event): void
    {
        if (! self::$sentNotifications->offsetExists($event->notification)) {
            return;
        }

        self::$sentNotifications[$event->notification]->markAsSent();
    }

    protected function shouldLog(NotificationSending $event): bool
    {
        $notification = $event->notification;

        if (method_exists($notification, 'shouldLog')) {
            return $notification->shouldLog($event);
        }

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
