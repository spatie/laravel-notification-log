<?php

namespace Spatie\NotificationLog\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Spatie\NotificationLog\Exceptions\InvalidNotifiable;
use Spatie\NotificationLog\Support\NotificationHistoryQueryBuilder;

trait HasHistory
{
    public function wasSentTo($notifiable, $withSameFingerprint = false): NotificationHistoryQueryBuilder
    {
        $this->ensureNotifiableIsModel($notifiable);

        return new NotificationHistoryQueryBuilder(
            $this,
            $notifiable,
            shouldExist: true,
            withSameFingerprint: $withSameFingerprint
        );
    }

    public function wasNotSentTo(
        $notifiable,
        $withSameFingerprint = false
    ): NotificationHistoryQueryBuilder {
        $this->ensureNotifiableIsModel($notifiable);

        return new NotificationHistoryQueryBuilder(
            $this,
            $notifiable,
            shouldExist: false,
            withSameFingerprint: $withSameFingerprint,
        );
    }

    protected function ensureNotifiableIsModel($notifiable): void
    {
        if (! $notifiable instanceof Model) {
            throw InvalidNotifiable::shouldBeAModel();
        }

        if (! in_array(HasNotifiableHistory::class, class_uses_recursive($notifiable))) {
            throw InvalidNotifiable::shouldUseTrait($notifiable);
        }
    }
}
