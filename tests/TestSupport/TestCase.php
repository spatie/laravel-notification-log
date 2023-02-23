<?php

namespace Spatie\NotificationLog\Tests\TestSupport;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\NotificationLog\NotificationLogServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('mail.driver', 'log');

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\NotificationLog\\Tests\\TestSupport\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            NotificationLogServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../../database/migrations/create_notification_log_items_table.php';

        $migration->up();

        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('email');
            $table->string('password');

            $table->timestamps();
        });
    }
}
