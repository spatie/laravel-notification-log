<?php

namespace Spatie\NotificationLog\Actions;

use Illuminate\Notifications\Events\NotificationSending;
use Spatie\NotificationLog\Exceptions\InvalidExtraContent;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Support\Config;

class ConvertNotificationSendingEventToLogItem
{
    public function execute(NotificationSending $event): ?NotificationLogItem
    {
        /** @var \Illuminate\Notifications\Notifiable $notifiable */
        $notifiable = $event->notifiable;

        $modelClass = Config::modelClass();

        return $modelClass::create([
            'notification_type' => $this->getNotificationType($event),
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id'  => $notifiable->id,
            'channel' => $event->channel,
            'extra' => $this->getExtra($event),
        ]);
    }

    protected function getNotificationType(NotificationSending $event): string
    {
        return get_class($event->notification);
    }

    /**
     * @return class-string<NotificationLogItem>
     */
    protected function getModelClass(NotificationSending $event): string
    {
        return config('notification-log.model');
    }

    /**
     * @return class-string<NotificationLogItem>
     */
    protected function getExtra(NotificationSending $event): array
    {
        $notification = $event->notification;

        if (method_exists($notification, 'logExtra', )) {
            $extra = $notification->logExtra($event);

            if (! is_array($extra)) {
                throw InvalidExtraContent::make($notification);
            }

            return $extra;
        }

        return [];
    }
}
