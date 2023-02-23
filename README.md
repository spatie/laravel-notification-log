# Log all notifications sent by your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-notification-log.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-notification-log)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-notification-log/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spatie/laravel-notification-log/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-notification-log/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/spatie/laravel-notification-log/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-notification-log.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-notification-log)

This package will log all the notifications sent by your app. This will allow you to write logic based on the notifications your app has sent.

If you want to create a list of all notifications sent to a user

```php
// returns a collection of `NotificationLogItem` models
$sentNotifications = $user->loggedNotifications();
```

In a view you could write this:

```blade
<ul>
@foreach($sentNotifications as $sentNotification)
    <li>{{ $sentNotification->type }} at {{ $sentNotification->sent_at->form('Y-m-d H:i:s') }}</li>
@endforeach
</ul>
```

The package also contains handy methods that allow you to make decisions based on the notifications sent. Here's an example, where we use the `wasAlreadySentTo` method provided by the package in a `shouldSent` method of a notification.

```php
// in a notification

public function shouldSend($notifiable)
{
      return ! $this
        ->wasAlreadySentTo($notifiable)
        ->inThePastMinutes(60);
}
```

You can fully customize which notifications get logged and how they get logged.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-notification-log.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-notification-log)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

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
        'convertEventToModel' => Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItem::class
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

## Usage

By default, the package will write an entry in the `notification_log_items` table for each [notification](TODO: add link to notification docs in laravel) sent in your app.

This table has these columns:



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
