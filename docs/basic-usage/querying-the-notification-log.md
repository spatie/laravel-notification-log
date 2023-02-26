---
title: Querying the notification log
weight: 3
---

For each notification sent in your app, a row in the `notification_log_items` table will be created. You can use the `Spatie\NotificationLog\Models\NotificationLogItem` model to query logged notifications.

```php
use Spatie\NotificationLog\Models\NotificationLogItem;

// returns all logged notifications
NotificationLogItem::query()->orderByDesc('id')->get();
```

## Getting the latest log item for a notifiable

The model has a handy `latestFor` method that will return the single latest logged notification for a given notifiable.

```php
/*
 * Will return the single most recent sent log item for the given notifiable.
 * If there was no notification sent yet to the notifiable, `null` will be returned.
 */
$logItem = NotificationLogItem::latestFor($notifiable);
```

The `latestFor` has a couple of optional parameters to search for the latest log item that corresponds to the given restrictions. 

```php
use Spatie\NotificationLog\Models\NotificationLogItem;
use App\Notifications\OrderSentNotification;

$logItem = NotificationLogItem::latestFor(
    $notifiable,
    notificationType: OrderSentNotification::class, // search for a specific notification type
    before: $carbon, // we're looking for a notification before the given carbon instance
    after: $carbon, // we're looking for a notification after the given carbon instance
    fingerprint: 'dummy-fingerprint' // search for a log item with this fingerprint
);
```

The `notificationType` parameter can be an array, in which case `latestFor` will return a log item with notification type that was most recently sent.

### Using the notifiable to search logged notifications

The package provides a trait `Spatie\NotificationLog\Models\Concerns\HasNotifiableHistory` that you can use on a notifiable.

```php
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Notifiable;
    use HasNotifiableHistory;
}
```

This trait will add a method `notificationLogItems` that will return a `MorphMany` relation to all notifications that were logged for the notifiable.

```php
/*
 * return all logged notifications, the most recent are first
 */
$loggedNotifications = $user->notificationLogItems;

/*
 * return the five most recent logged notifications
 */
$loggedNotifications = $user->notificationLogItems()->limit(5)->get();
```

Additionally, the trait provides a `latestLoggedNotification` method that will return the latest logged notification for the notifiable.

```php
/*
 * Will return the single most recent sent log item for the given notifiable.
 * If there was no notification sent yet to the notifiable, `null` will be returned.
 */

$logItem = $user->latestLoggedNotification();
```

The method has a couple of optional parameters to search for the latest log item that corresponds to the given restrictions.

```php
use App\Notifications\OrderSentNotification;

$logItem = $user->latestLoggedNotification(
    notificationType: OrderSentNotification::class, // search for a specific notification type
    before: $carbon, // we're looking for a notification before the given carbon instance
    after: $carbon, // we're looking for a notification after the given carbon instance
    fingerprint: 'dummy-fingerprint' // search for a log item with this fingerprint
);
```

The `notificationType` parameter can be an array, in which case `latestFor` will return a log item with notification type that was most recently sent.

## Determining sent notifications within a notification

The package offers a `HasHistory` trait that contains a couple of functions that allow you to determine if a similar notification was recently sent.

To get started, first add the trait to your notification.

```php
namespace App\Notifications;

use Illuminate\Notifications\Notificationuse Spatie\NotificationLog\Models\Concerns\HasHistory;

class YourNotification extends Notification
{
    use HasHistory;
}
```

Imagine that your notification should only be sent if a similar notification wasn't recently sent.

```php
public function shouldSend($notifiable)
{
    $this
       ->wasNotSentTo($notifiable)
       ->inThePastMinutes(30);
}
```

By default, `wasNotSentTo` will not take into account [the fingerprint of a logged notification](/docs/laravel-notification-log/v1/basic-usage/working-with-fingerprints). To take it into account, set the named argument `withSameFingerprint` to true.

```php
public function shouldSend($notifiable)
{
    $this
       ->wasNotSentTo($notifiable, withSameFingerprint: true)
       ->inThePastMinutes(30);
}
```

In addition to `wasNotSentTo`, the trait also has a method `wasSentTo`.
