---
title: Installation & setup
weight: 4
---

You can install the package via composer:

```bash
composer require spatie/laravel-notification-log
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="notification-log-migrations"
php artisan migrate
```

### Publishing the config file

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-notification-log-config"
```

This is the contents of the published config file:

```php
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
        'convertEventToModel' => Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItemAction::class
    ],

    /*
     * The event subscriber that will listen for the notification events fire by Laravel.
     * In most cases, you don't need to touch this. You could replace this by
     * a class of your own that extends the original.
     */
    'event_subscriber' => Spatie\NotificationLog\NotificationEventSubscriber::class,
];
```

### Pruning results

This package will store all sent notifications in the `notification_log_items` table. The related `NotificationLogItems` models uses the [Laravel's `MassPrunable` trait](https://laravel.com/docs/10.x/eloquent#mass-pruning). In the `notification-log` config file, you can specify the maximum age of a model in the `prune_after_days` key. Don't forget to schedule the `model:prune` command, as instructed in Laravel's docs. You'll have to explicitly add the model class:

```php
// in app/Console/Kernel.php

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('model:prune', [
                    '--model' => [
                        \Spatie\NotificationLog\Models\NotificationLogItem::class,
                    ],
        ])->daily();
    
        // ...   
    }
}
```
