<?php

namespace Spatie\NotificationLog\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class NotificationLogItem extends Model
{
    use HasFactory;
    use MassPrunable;

    protected $guarded = [];

    protected $casts = [
        'extra' => 'array',
        'anonymous_notifiable_properties' => 'array',
        'send_at' => 'datetime',
    ];

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

    public function markAsSent(): self
    {
        $this->update([
            'sent_at' => now(),
        ]);

        return $this;
    }

    public static function latestFor(
        $notifiable,
        string $fingerprint = null,
        string|array $notificationType = null,
        Carbon $before = null,
        Carbon $after = null,
    ): ?NotificationLogItem {
        return self::query()
            ->where('notifiable_type', $notifiable->getMorphClass())
            ->where('notifiable_id', $notifiable->getKey())
            ->when($fingerprint, fn (Builder $query) => $query->where('fingerprint', $fingerprint))
            ->when($notificationType, function (Builder $query) use ($notificationType) {
                $query->whereIn('notification_type', Arr::wrap($notificationType));
            })
            ->when($before, function (Builder $query) use ($before) {
                $query->where('created_at', '<', $before->toDateTimeString());
            })
            ->when($after, function (Builder $query) use ($after) {
                $query->where('created_at', '>', $after->toDateTimeString());
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();
    }
}
