<?php

use Illuminate\Database\Eloquent\Builder;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\CustomNotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('prunable returns an eloquent builder instance', function () {
    $model = new NotificationLogItem();
    $query = $model->prunable();

    expect($query)->toBeInstanceOf(Builder::class);
    expect($query->getModel())->toBeInstanceOf(NotificationLogItem::class);
});

it('prunable works with custom notification log item models', function () {
    config()->set('notification-log.model', CustomNotificationLogItem::class);

    $model = new CustomNotificationLogItem();
    $query = $model->prunable();

    expect($query)->toBeInstanceOf(Builder::class);
    expect($query->getModel())->toBeInstanceOf(CustomNotificationLogItem::class);
});

it('prunable filters by created_at correctly', function () {
    config()->set('notification-log.prune_after_days', 30);

    $oldItem = NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['created_at' => now()->subDays(31)]);

    $newItem = NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['created_at' => now()->subDays(29)]);

    $model = new NotificationLogItem();
    $prunableItems = $model->prunable()->get();

    expect($prunableItems)->toHaveCount(1);
    expect($prunableItems->first()->id)->toBe($oldItem->id);
});

it('latestForQuery returns an eloquent builder instance', function () {
    $query = NotificationLogItem::latestForQuery($this->user);

    expect($query)->toBeInstanceOf(Builder::class);
    expect($query->getModel())->toBeInstanceOf(NotificationLogItem::class);
});

it('latestForQuery works with custom notification log item models', function () {
    config()->set('notification-log.model', CustomNotificationLogItem::class);

    $query = CustomNotificationLogItem::latestForQuery($this->user);

    expect($query)->toBeInstanceOf(Builder::class);
    expect($query->getModel())->toBeInstanceOf(CustomNotificationLogItem::class);
});

it('latestForQuery handles null fingerprint correctly', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['fingerprint' => 'test-fingerprint']);

    $result = NotificationLogItem::latestForQuery($this->user, fingerprint: null)->first();

    expect($result)->not()->toBeNull();
    expect($result->fingerprint)->toBe('test-fingerprint');
});

it('latestForQuery handles empty string fingerprint correctly', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['fingerprint' => '']);

    $result = NotificationLogItem::latestForQuery($this->user, fingerprint: '')->first();

    expect($result)->not()->toBeNull();
    expect($result->fingerprint)->toBe('');
});

it('latestForQuery handles null notificationType correctly', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['notification_type' => 'TestNotification']);

    $result = NotificationLogItem::latestForQuery($this->user, notificationType: null)->first();

    expect($result)->not()->toBeNull();
    expect($result->notification_type)->toBe('TestNotification');
});

it('latestForQuery handles null channel correctly', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['channel' => 'mail']);

    $result = NotificationLogItem::latestForQuery($this->user, channel: null)->first();

    expect($result)->not()->toBeNull();
    expect($result->channel)->toBe('mail');
});

it('latestForQuery handles null before date correctly', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['created_at' => now()->subDays(5)]);

    $result = NotificationLogItem::latestForQuery($this->user, before: null)->first();

    expect($result)->not()->toBeNull();
});

it('latestForQuery handles null after date correctly', function () {
    NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['created_at' => now()->subDays(5)]);

    $result = NotificationLogItem::latestForQuery($this->user, after: null)->first();

    expect($result)->not()->toBeNull();
});

it('latestForQuery orders by created_at and primary key', function () {
    $first = NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['created_at' => now()->subMinutes(2)]);

    $second = NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['created_at' => now()->subMinutes(1)]);

    $third = NotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['created_at' => now()->subMinutes(1)]);

    $results = NotificationLogItem::latestForQuery($this->user)->get();

    expect($results[0]->id)->toBe($third->id);
    expect($results[1]->id)->toBe($second->id);
    expect($results[2]->id)->toBe($first->id);
});

it('prunable preserves custom model scopes', function () {
    config()->set('notification-log.model', CustomNotificationLogItem::class);
    config()->set('notification-log.prune_after_days', 30);

    $confirmedOld = CustomNotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create([
            'created_at' => now()->subDays(31),
            'confirmed_at' => now()->subDays(31),
        ]);

    $unconfirmedOld = CustomNotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create([
            'created_at' => now()->subDays(31),
            'confirmed_at' => null,
        ]);

    $model = new CustomNotificationLogItem();
    $prunableConfirmed = $model->prunable()->confirmed()->get();
    $prunableUnconfirmed = $model->prunable()->unconfirmed()->get();

    expect($prunableConfirmed)->toHaveCount(1);
    expect($prunableConfirmed->first()->id)->toBe($confirmedOld->id);

    expect($prunableUnconfirmed)->toHaveCount(1);
    expect($prunableUnconfirmed->first()->id)->toBe($unconfirmedOld->id);
});

it('latestForQuery preserves custom model scopes', function () {
    config()->set('notification-log.model', CustomNotificationLogItem::class);

    $confirmed = CustomNotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['confirmed_at' => now()]);

    $unconfirmed = CustomNotificationLogItem::factory()
        ->forNotifiable($this->user)
        ->create(['confirmed_at' => null]);

    $confirmedResult = CustomNotificationLogItem::latestForQuery($this->user)->confirmed()->first();
    $unconfirmedResult = CustomNotificationLogItem::latestForQuery($this->user)->unconfirmed()->first();

    expect($confirmedResult)->not()->toBeNull();
    expect($confirmedResult->id)->toBe($confirmed->id);

    expect($unconfirmedResult)->not()->toBeNull();
    expect($unconfirmedResult->id)->toBe($unconfirmed->id);
});

