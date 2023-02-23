<?php

namespace Spatie\NotificationLog\Exceptions;

use Exception;

class InvalidActionClass extends Exception
{
    public static function make(string $actionKey, string $expectedToExtendClass): self
    {
        return new self("The class you put in the `notification-log.actions.{$actionKey}` config key is invalid. A valid class should extend `$expectedToExtendClass`.");
    }
}
