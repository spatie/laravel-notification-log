---
title: Working with fingerprints
weight: 3
---

Notification typically accept parameters in the constructor that will be used to determine the message of the notification.

```php 
use Illuminate\Notifications\Notification;

class OrderSentNotification extends Notification
{
    public function __construct(
        public Order $order,
    ) {}
```

To log that a notification was sent for a set of constructor parameters, you can add a fingerprint. A fingerprint is a simple string that will be logged along with the sent notification.

To add a signature to a notification, add a function `fingerprint` to your notification.

```php 
use Illuminate\Notifications\Notification;

class OrderSentNotification extends Notification
{
    public function __construct(
        public Order $order,
    ) {}
    
    public function fingerprint()
    {
        return "order-{$this->order->id}";
    }
}
```

The fingerprint will be saved in the `fingerprint` on the log item that is created when the notification is sent.

```php
// returns the fingerprint
Spatie\NotificationLog\Models\NotificationLogItem::first()->fingerprint;
```

You can use the fingerprint to hunt down notifications using [the `latestFor` method](/docs/laravel-notification-log/v1/basic-usage/querying-the-notification-log).

```php
// return the latest logged notification for the first order.

$logItem = NotificationLogItem::latestFor($notifiable, fingerprint: "order-1");
```

When your notification has a lot, or complex, constructor parameters, you could use a hashing function like `md5` to generate a value that is unique for those parameters.

```php 
use Illuminate\Notifications\Notification;

class OrderSentNotification extends Notification
{
    public function __construct(
        public string $parameter,
        public string $anotherParameter,
        public string $yetAnotherOne,

    ) {}
    
    public function fingerprint()
    {
        return md5("{$this-parameter}-{$this->anotherParameter}-{$yetAnotherOne}";
    }
}
```
