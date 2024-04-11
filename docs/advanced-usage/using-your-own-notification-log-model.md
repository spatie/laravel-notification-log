---
title: Using your own notification log model
weight: 3
---

By using your own notification log model, you can customize things like the database connection to be used, the pruning strategy and more.

To get started, create a class of your own that extends the `NotificationLogItem`. On that class you can add another property or function to customize behaviour.

```php
use Spatie\NotificationLog\Models\NotificationLogItem;

class CustomLogItem extends NotificationLogItem
{
    
}
```

To let the package use your model, specify it in the `model` key of the `notification-log` config file.

```php
return [
    /*
     * This model will be used to log all sent notifications
     */
    'model' => CustomLogItem::class,
    
    // ...
];
```
