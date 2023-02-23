<?php

namespace Spatie\NotificationLog\Support;

use Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItem;
use Spatie\NotificationLog\Exceptions\InvalidActionClass;
use Spatie\NotificationLog\NotificationEventSubscriber;
use Spatie\NotificationLog\Models\NotificationLogItem;

class Config
{
    /**
     * @return class-string<NotificationLogItem>
     */
    public static function modelClass(): string
    {
        return config('notification-log.model');
    }

    public static function convertEventToModelAction(): ConvertNotificationSendingEventToLogItem
    {
        $actionClass = config('notification-log.actions.convertEventToModel');

        if (! is_a($actionClass, ConvertNotificationSendingEventToLogItem::class, true)) {
            throw InvalidActionClass::make('convertEventToModel', ConvertNotificationSendingEventToLogItem::class);
        }

        return app($actionClass);
    }

    /**
     * @return class-string<NotificationEventSubscriber>
     */
    public static function eventSubscriberClass(): string
    {
        return config('notification-log.event_subscriber');
    }
}
