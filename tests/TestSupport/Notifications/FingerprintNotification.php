<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Notifications;

class FingerprintNotification extends TestNotification
{
    public function fingerprint(): ?string
    {
        return 'this-is-a-fingerprint';
    }
}
