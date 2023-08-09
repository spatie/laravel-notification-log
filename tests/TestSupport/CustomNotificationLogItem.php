<?php

namespace Spatie\NotificationLog\Tests\TestSupport;

use Spatie\NotificationLog\Models\NotificationLogItem;

class CustomNotificationLogItem extends NotificationLogItem
{
    public $table = 'notification_log_items';
}
