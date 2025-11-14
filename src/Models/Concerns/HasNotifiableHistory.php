<?php

namespace Spatie\NotificationLog\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Support\Config;

/** @mixin Model $this */
trait HasNotifiableHistory
{
    public function latestLoggedNotification(
        ?string $fingerprint = null,
        string|array|null $notificationTypes = null,
        ?Carbon $before = null,
        ?Carbon $after = null,
        string|array|null $channel = null,
    ): ?NotificationLogItem {
        return $this->latestLoggedNotificationQuery(...func_get_args())->first();
    }

    public function latestLoggedNotificationQuery(
        ?string $fingerprint = null,
        string|array|null $notificationTypes = null,
        ?Carbon $before = null,
        ?Carbon $after = null,
        string|array|null $channel = null,
    ): Builder {
        $notificationLogClass = Config::notificationLogModelClass();

        return $notificationLogClass::latestForQuery(
            $this,
            ...func_get_args(),
        );
    }

    public function notificationLogItems(): MorphMany
    {
        $notificationLogClass = Config::notificationLogModelClass();

        $logKeyName = (new $notificationLogClass)->getKeyName();

        return $this->morphMany($notificationLogClass, 'notifiable')
            ->orderByDesc('created_at')
            ->orderByDesc($logKeyName);
    }
}
