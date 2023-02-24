---
title: Customizing the logging process
weight: 3
---

The `ConvertNotificationSendingEventToLogItemAction` class determines how notifications will get logged by default. It contains many methods that can be overridden to customize how notifications will get logged.

To override methods, start by creating a class of your own that extends the default `ConvertNotificationSendingEventToLogItemAction` action. Override any method you want from the base class.

In the following example, we'll create a custom action that will use the base name of a class instead of the fqcn to use as the type.

```php
use Spatie\NotificationLog\Actions\ConvertNotificationSendingEventToLogItemAction;

class CustomConversionAction extends ConvertNotificationSendingEventToLogItemAction
{
    protected function getNotificationType(NotificationSending $event): string
    {
        $notification = $event->notification;

        return class_basename($notification);
    }
}
```

You should register your custom class in the `actions.convertEventToModel` key of the `notification-log` config file.

```php 
return [
    // ...

    'actions' => [
        'convertEventToModel' => CustomConversionAction::class,
    ],
]
```
