<?php

use Illuminate\Database\Eloquent\Model;
use Spatie\NotificationLog\Tests\TestSupport\TestCase;

uses(TestCase::class)->in(__DIR__);

expect()->extend('toBeModel', function (object|null $model) {
    if (is_null($model)) {
        return false;
    }

    if (! $model instanceof Model) {
        return false;
    }

    if (get_class($this->value) !== get_class($model)) {
        return false;
    }

    expect($this->value)->toBeInstanceOf($model::class);

    expect($this->value->id)->toBe($model->id);

    if ($this->value->id !== $model->id) {
        return false;
    }

    return true;
});

/**
 * sent_at naamgeving verbeteren
 * exceptions opvangen uitzoeken
 *
 * $notifiable->latestLoggedNotificationOfType([...]);

$notifiable->latestLoggedNotification();

$notifiable->loggedNotifications();

$this
->wasAlreadySentTo($notifiable)
->inThePastMinutes(60);

$logItem->wasSentAfter($logItem);

$logitem->wasSentBefore($logItem);

$logItemWasSentBefore();

$logItem->
 */
