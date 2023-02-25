<?php

namespace Spatie\NotificationLog\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Support\Config;

/** @mixin Model $this */
trait NotifiableNotificationHistory
{
    public function latestLoggedNotification(
        string $fingerprint = null,
        string|array $notificationTypes = null,
        Carbon $before = null,
        Carbon $after = null,
    ): ?NotificationLogItem {
        $notificationLogClass = Config::notificationLogModelClass();

        return $notificationLogClass::latestFor(
            $this,
            ...func_get_args()
        );
    }

    public function notificationLogItems(): MorphMany
    {
        return $this->morphMany(NotificationLogItem::class, 'notifiable')
            ->orderByDesc('created_at')
            ->orderByDesc($this->getKeyName());
    }
}
