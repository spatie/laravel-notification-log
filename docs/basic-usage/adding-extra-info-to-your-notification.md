---
title: Adding extra info to a logged notifications
weight: 3
---

The `notification_log_items` table has a JSON column called `extra` that allows you to store any value you want.

To fill up the `extra` column, add a function named `logExtra` to your notification and let it return an array that should be stored in the `extra` column when the notification gets logged.

```php
use Illuminate\Notifications\Events\NotificationSending;

// in a notification
public function logExtra(NotificationSending $event): array
{
    return ['extraKey' => 'extraValue'];
}
```
