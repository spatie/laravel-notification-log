---
title: Determining which notifications get logged
weight: 2
---

By default, the package will log all notifications sent by your app. To turn off this behaviour, set the `log_all_by_default` key in the `notification-log` config file to `false`.

You can determine per notification whether it should be logged or not by adding a `shouldLog` method to your notification. If you return a truthy value the notification will be logged.

```php
use Illuminate\Notifications\Events\NotificationSending;

// in a notification
public function shouldLog(NotificationSending $event): bool
{
    return true;
}
```
