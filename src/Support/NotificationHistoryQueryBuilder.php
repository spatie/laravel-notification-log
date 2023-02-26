<?php

namespace Spatie\NotificationLog\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class NotificationHistoryQueryBuilder
{
    protected Builder $query;

    public function __construct(
        protected Notification $notification,
        protected Model $notifiable,
        protected bool $shouldExist,
        protected bool $withSameFingerprint,
    ) {
        $action = Config::convertEventToModelAction();

        $type = $action->getNotificationTypeForNotification($this->notification, $this->notifiable);

        $this->query = $this->notifiable
            ->latestLoggedNotificationQuery(
                notificationTypes: $type,
            );
    }

    public function inThePastMinutes(int $numberOfMinutes): bool
    {
        $query = $this->query
            ->where('created_at', '>=', now()->subMinutes($numberOfMinutes))
            ->when($this->withSameFingerprint, function(Builder $query) {
                $action = Config::convertEventToModelAction();

                $fingerprint = $action->getFingerprintForNotification(
                    $this->notification,
                    $this->notifiable,
                );

                if ($fingerprint === null) {
                    return;
                }

                $query->where('fingerprint', $fingerprint);
            });

        return $this->shouldExist
            ? $query->exists()
            : $query->doesntExist();
    }

    public function query(): Builder
    {
        return $this->query;
    }
}
