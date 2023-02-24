<?php

namespace Spatie\NotificationLog\Support;

use Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItemAction;
use Spatie\NotificationLog\Exceptions\InvalidActionClass;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\NotificationEventSubscriber;

class Config
{
    /**
     * @return class-string<NotificationLogItem>
     */
    public static function notificationLogModelClass(): string
    {
        return config('notification-log.model');
    }

    public static function convertEventToModelAction(): ConvertNotificationSendingEventToLogItemAction
    {
        $actionClass = config('notification-log.actions.convertEventToModel');

        if (! is_a($actionClass, ConvertNotificationSendingEventToLogItemAction::class, true)) {
            throw InvalidActionClass::make('convertEventToModel', ConvertNotificationSendingEventToLogItemAction::class);
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
