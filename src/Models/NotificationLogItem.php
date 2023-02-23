<?php

namespace Spatie\NotificationLog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationLogItem extends Model
{
    use MassPrunable;

    public function prunable(): Builder
    {
        $threshold = config('notification-log.prune_after_days');

        return static::where('created_at', '<=', now()->days($threshold));
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo('notifiable');
    }

    public function wasSentInPastMinutes(int $minutes = 1): bool
    {
        return $this->created_at > now()->subMinutes($minutes);
    }
}
