<?php

namespace Spatie\NotificationLog\Tests\TestSupport;

use Illuminate\Database\Eloquent\Builder;
use Spatie\NotificationLog\Models\NotificationLogItem;

class CustomNotificationLogItem extends NotificationLogItem
{
    public $table = 'notification_log_items';

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->whereNotNull('confirmed_at');
    }

    public function scopeUnconfirmed(Builder $query): Builder
    {
        return $query->whereNull('confirmed_at');
    }
}
