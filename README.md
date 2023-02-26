# Log notifications sent by your Laravel app

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
    <li>{{ $sentNotification->type }} at {{ $sentNotification->created_at->format('Y-m-d H:i:s') }}</li>
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

## Documentation

All documentation is available [on our documentation site](https://spatie.be/docs/laravel-notification-log).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
