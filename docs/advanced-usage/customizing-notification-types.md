---
title: Customizing notification types
weight: 3
---

By default, the package will use the fully qualified class name of a notification to fill the `notification_type` column in the `notification_log_items` table.

You can customize this by adding a method `logType` to your notification and let it return the value that should be saved in the `notification_type` column.

```php
// in a notification

public function logType(): string
{
    return 'customType';
}
```
