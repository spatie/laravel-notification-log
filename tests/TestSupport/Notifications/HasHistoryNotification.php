<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

use Closure;
use Spatie\NotificationLog\Models\Concerns\HasHistory;

class HasHistoryNotification extends TestNotification
{
    use HasHistory;

    protected static Closure $historyTestCallable;

    public static function setHistoryTestCallable(callable $historyTestCallable)
    {
        self::$historyTestCallable = $historyTestCallable;
    }

    public function historyTest($notifiable): bool
    {
        return (self::$historyTestCallable)($notifiable);
    }

    public function fingerprint()
    {
        return 'my-fingerprint';
    }
}
