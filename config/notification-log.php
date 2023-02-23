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
];
