---
title: Introduction
weight: 1
---

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
    <li>{{ $sentNotification->notification_type }} at {{ $sentNotification->created_at->format('Y-m-d H:i:s') }}</li>
@endforeach
</ul>
```

The package also contains handy methods that allow you to make decisions based on the notifications sent. Here's an example, where we use the `wasAlreadySentTo` method provided by the package in the `shouldSend` method of a notification.

```php
use Spatie\NotificationLog\Models\Concerns\HasHistory;

// in a notification

// set history trait
use HasHistory;

// verifying if the queued notification can be sent based in the history
public function shouldSend($notifiable)
{
      return ! $this
        ->wasSentTo($notifiable)
        ->inThePastMinutes(60);
}
```

You can fully customize which notifications get logged and how they get logged.

