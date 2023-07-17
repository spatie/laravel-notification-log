<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

use Closure;
use Illuminate\Support\Arr;
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
        return app()->call(self::$historyTestCallable, [
            'notifiable' => $notifiable,
            'channel' => Arr::first($this->via($notifiable)),
        ]);
    }

    public function fingerprint()
    {
        return 'my-fingerprint';
    }
}
