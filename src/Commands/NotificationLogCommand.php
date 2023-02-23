<?php

namespace Spatie\NotificationLog\Commands;

use Illuminate\Console\Command;

class NotificationLogCommand extends Command
{
    public $signature = 'laravel-notification-log';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
