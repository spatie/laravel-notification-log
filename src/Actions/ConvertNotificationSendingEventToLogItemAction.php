<?php

namespace Spatie\NotificationLog\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Events\NotificationSending;
use Spatie\NotificationLog\Exceptions\InvalidExtraContent;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Support\Config;

class ConvertNotificationSendingEventToLogItemAction
{
    public function execute(NotificationSending $event): ?NotificationLogItem
    {
        $modelClass = Config::notificationLogModelClass();

        return $modelClass::create([
            'notification_type' => $this->getNotificationType($event),
            'notifiable_type' => $this->getNotifiableType($event),
            'notifiable_id' => $this->getNotifiableKey($event),
            'channel' => $event->channel,
            'fingerprint' => $this->getFingerPrint($event),
            'extra' => $this->getExtra($event),
            'anonymous_notifiable_properties' => $this->getAnonymousNotifiableProperties($event),
        ]);
    }

    protected function getNotifiableType(NotificationSending $event): ?string
    {
        /** @var Model|AnonymousNotifiable $notifiable */
        $notifiable = $event->notifiable;

        return $notifiable instanceof Model
            ? $notifiable->getMorphClass()
            : null;
    }

    protected function getNotifiableKey(NotificationSending $event): mixed
    {
        /** @var Model|AnonymousNotifiable $notifiable */
        $notifiable = $event->notifiable;

        return $notifiable instanceof Model
            ? $notifiable->getKey()
            : null;
    }

    protected function getNotificationType(NotificationSending $event): string
    {
        $notification = $event->notification;

        if (method_exists($notification, 'logType')) {
            return $notification->logType($event);
        }

        return get_class($notification);
    }

    /**
     * @return class-string<NotificationLogItem>
     */
    protected function getModelClass(): string
    {
        return config('notification-log.model');
    }

    protected function getFingerprint(NotificationSending $event): ?string
    {
        $notification = $event->notification;

        if (method_exists($notification, 'fingerprint')) {
            return $notification->fingerprint($event);
        }

        return null;
    }

    protected function getExtra(NotificationSending $event): array
    {
        $notification = $event->notification;

        if (method_exists($notification, 'logExtra')) {
            $extra = $notification->logExtra($event);

            if (! is_array($extra)) {
                throw InvalidExtraContent::make($notification);
            }

            return $extra;
        }

        return [];
    }

    protected function getAnonymousNotifiableProperties(NotificationSending $event): ?array
    {
        if (! $event->notifiable instanceof AnonymousNotifiable) {
            return null;
        }

        return $event->notifiable->routes;
    }
}
