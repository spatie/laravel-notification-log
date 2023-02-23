<?php

namespace Spatie\NotificationLog\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\NotificationLog\NotificationLog;

/**
 * @see \Spatie\NotificationLog\NotificationLog
 */
class NotificationHistory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return NotificationLog::class;
    }
}
