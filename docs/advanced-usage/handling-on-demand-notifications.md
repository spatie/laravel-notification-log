---
title: Handling on demand notifications
weight: 3
---

Laravel has a feature called "[On Demand Notifications](https://laravel.com/docs/12.x/filesystem#on-demand-disks)", that allows you to send a notification to a notifiable that is not backed by a user model.

```php
Notification::route('mail', 'taylor@example.com');
    ->notify(new InvoicePaidNotification($invoice))
```

When sending an on demand notification, the resulting entry in the `notification_log_items` table, will have `notifiable_id` and `notifiable_type` set to `null`. You'll find the configuration of your on demand notification in the `anonymous_notifiable_properties` property.
