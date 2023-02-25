---
title: Introduction
weight: 1
---

**PACKAGE IN DEVELOPMENT, DO NOT USE YET**

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

