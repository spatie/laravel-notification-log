<?php

namespace Spatie\NotificationLog\Exceptions;

use Exception;
use Illuminate\Notifications\Notification;

class InvalidExtraContent extends Exception
{
    public static function make(Notification $notification): self
    {
        $notificationClass = $notification::class;

        return new self("The `logExtra` method of `$notificationClass` did not return an array. Only an array is accepted.");
    }
}
