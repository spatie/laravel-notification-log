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

Rest coming soon...

