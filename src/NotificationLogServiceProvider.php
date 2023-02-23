<?php

namespace Spatie\NotificationLog;

use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\NotificationLog\Support\Config;

class NotificationLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-notification-log')
            ->hasConfigFile()
            ->hasMigration('create_notification_log_items_table');
    }

    public function bootingPackage(): void
    {
        $eventSubscriberClass = Config::eventSubscriberClass();

        Event::subscribe($eventSubscriberClass);
    }
}
