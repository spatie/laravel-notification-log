---
title: Getting started
weight: 1
---

By default, the package will write an entry in the `notification_log_items` table for each [notification](TODO: add link to notification docs in laravel) sent in your app. You can also have fine-grained control over [which notifications should be logged](TODO: add link).

the `notification_log_items` table has these columns:

- `notification_type`: the class name of the sent notification
- `notifiable_id`: the key value of the notifiable the notification was sent to
- `notifiable_type`: the (morph) class name of the notifiable the notification was sent to
- `channel`: the name of the channel the notification was sent to
- `fingerprint`: a value that you can customize to identify the exact content of the notification
- `extra`: an array of values you can freely add to the log
- `anonymous_notifiable_properties`: the configuration of the notifiable when sending an [on demand notification](TODO: link to docs)
- 
  How the package fills all these properties can be fully customized.
