<?php

namespace Spatie\NotificationLog\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Spatie\NotificationLog\Support\NotificationHistoryQueryBuilder;

trait HasHistory
{
    public function wasAlreadySentTo($notifiable): NotificationHistoryQueryBuilder
    {
        $this->ensureNotifiableIsModel($notifiable);

        $notifiable->latestLoggedNotification();

        return new NotificationHistoryQueryBuilder($notifiable, shouldExist: true);
    }

    public function wasNotSentTo($notifiable): NotificationHistoryQueryBuilder
    {
        $this->ensureNotifiableIsModel($notifiable);

        return new NotificationHistoryQueryBuilder($notifiable, shouldExist: false);
    }

    protected function ensureNotifiableIsModel($notifiable): void
    {
        if (! $notifiable instanceof Model) {
            // todo: throw exception
        }

        if (! in_array(HasNotifiableHistory::class, class_uses_recursive($notifiable))) {
            // todo: throw exception
        }
    }

    /**
     * $this
    ->wasAlreadySentTo($notifiable)
    ->inThePastMinutes(60);
     */
}
