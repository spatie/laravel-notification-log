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

The package provides a trait `Spatie\NotificationLog\Models\Concerns\NotifiableNotificationHistory` that you can use on a notifiable.

```php
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Notifiable;
    use NotifiableNotificationHistory;
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
