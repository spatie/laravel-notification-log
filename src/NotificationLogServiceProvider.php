<?php

namespace Spatie\NotificationLog;

use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\NotificationLog\Commands\NotificationLogCommand;

class NotificationLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-notification-log')
            ->hasConfigFile()
            ->hasMigration('create_notification_log_items_table');
    }

    public function registeringPackage()
    {
        Event::subscribe(NotificationEventSubscriber::class);
    }
}
