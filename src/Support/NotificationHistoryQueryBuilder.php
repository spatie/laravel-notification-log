<?php

namespace Spatie\NotificationLog\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotificationHistoryQueryBuilder
{
    protected Builder $query;

    public function __construct(
        protected Model $notifiable,
        protected bool $shouldExist,
    )
    {
        $this->query = $this->notifiable->latestLoggedNotificationQuery();
    }

    public function inThePastMinutes(int $numberOfMinutes)
    {
        $query = $this->query
            ->where('created_at', '>=', now()->subMinutes($numberOfMinutes));

        return $this->shouldExist
            ? $query->exists()
            : $query->doesntExist();
    }



    public function query(): Builder
    {
        return $this->query;
    }
}
