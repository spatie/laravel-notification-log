<?php

namespace Spatie\NotificationLog\Tests\Models\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\NotificationLog\Models\NotificationLogItem;
use Spatie\NotificationLog\Tests\TestSupport\Models\User;
use Spatie\NotificationLog\Tests\TestSupport\Models\UuidUser;

beforeEach(function () {
    // Create uuid_users table for UUID-based notifiables
    if (! Schema::hasTable('uuid_users')) {
        Schema::create('uuid_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });
    }

    $this->regularUser = User::factory()->create();
    $this->uuidUser = UuidUser::factory()->create();
});

it('notificationLogItems orders by log item primary key for regular users', function () {
    $first = NotificationLogItem::factory()
        ->forNotifiable($this->regularUser)
        ->create(['created_at' => now()->subMinutes(2)]);

    $second = NotificationLogItem::factory()
        ->forNotifiable($this->regularUser)
        ->create(['created_at' => now()->subMinutes(1)]);

    $third = NotificationLogItem::factory()
        ->forNotifiable($this->regularUser)
        ->create(['created_at' => now()->subMinutes(1)]);

    $items = $this->regularUser->notificationLogItems()->get();

    expect($items)->toHaveCount(3);
    // Should be ordered by created_at desc, then by log item id desc
    expect($items[0]->id)->toBe($third->id);
    expect($items[1]->id)->toBe($second->id);
    expect($items[2]->id)->toBe($first->id);
});

it('notificationLogItems orders by log item primary key for UUID notifiables', function () {
    $first = NotificationLogItem::factory()
        ->forNotifiable($this->uuidUser)
        ->create(['created_at' => now()->subMinutes(2)]);

    $second = NotificationLogItem::factory()
        ->forNotifiable($this->uuidUser)
        ->create(['created_at' => now()->subMinutes(1)]);

    $third = NotificationLogItem::factory()
        ->forNotifiable($this->uuidUser)
        ->create(['created_at' => now()->subMinutes(1)]);

    $items = $this->uuidUser->notificationLogItems()->get();

    expect($items)->toHaveCount(3);
    // Should be ordered by created_at desc, then by log item id desc (not by notifiable id)
    expect($items[0]->id)->toBe($third->id);
    expect($items[1]->id)->toBe($second->id);
    expect($items[2]->id)->toBe($first->id);
});

it('notificationLogItems works correctly with UUID notifiable keys', function () {
    // This test ensures the relation doesn't crash when the notifiable has a UUID key
    $logItem = NotificationLogItem::factory()
        ->forNotifiable($this->uuidUser)
        ->create();

    $items = $this->uuidUser->notificationLogItems()->get();

    expect($items)->toHaveCount(1);
    expect($items->first()->id)->toBe($logItem->id);
    expect($items->first()->notifiable_id)->toBe($this->uuidUser->getKey());
    expect($this->uuidUser->getKeyType())->toBe('string');
});

it('notificationLogItems maintains correct ordering when multiple items have same created_at', function () {
    $sameTime = now()->subMinutes(1);

    $first = NotificationLogItem::factory()
        ->forNotifiable($this->uuidUser)
        ->create(['created_at' => $sameTime]);

    $second = NotificationLogItem::factory()
        ->forNotifiable($this->uuidUser)
        ->create(['created_at' => $sameTime]);

    $third = NotificationLogItem::factory()
        ->forNotifiable($this->uuidUser)
        ->create(['created_at' => $sameTime]);

    $items = $this->uuidUser->notificationLogItems()->get();

    expect($items)->toHaveCount(3);
    // Should be ordered by log item id desc when created_at is the same
    expect($items[0]->id)->toBe($third->id);
    expect($items[1]->id)->toBe($second->id);
    expect($items[2]->id)->toBe($first->id);
});
