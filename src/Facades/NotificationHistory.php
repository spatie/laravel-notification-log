<?php

namespace Spatie\NotificationLog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\NotificationLog\NotificationLog
 */
class NotificationHistory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Spatie\NotificationLog\NotificationLog::class;
    }
}
