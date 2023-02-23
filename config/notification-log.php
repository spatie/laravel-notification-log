<?php

return [
    /*
     * This model will be used to log all sent notifications
     */
    'model' => Spatie\NotificationLog\Models\NotificationLogItem::class,

    /*
     * Log items older than this number of days will be automatically be removed.
     *
     * This feature uses Laravel's native pruning feature:
     * https://laravel.com/docs/10.x/eloquent#pruning-models
     */
    'prune_after_days' => 30,

    /*
     * If this is set to true, any notification that does not have a
     * `shouldLog` method will be logged.
     */
    'log_all_by_default' => config('notification-log.log_all_by_default'),

    /*
     * By overriding these actions, you can make low level customizations. You can replace
     * these classes by a class of your own that extends the original.
     *
     */
    'actions' => [
        'convertEventToModel' => Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItem::class,
    ],

    /*
     * The event subscriber that will listen for the notification events fire by Laravel.
     * In most cases, you don't need to touch this. You could replace this by
     * a class of your own that extends the original.
     */
    'event_subscriber' => Spatie\NotificationLog\NotificationEventSubscriber::class,
];
