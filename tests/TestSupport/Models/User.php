<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Spatie\NotificationLog\Models\Concerns\NotifiableNotificationHistory;

class User extends BaseUser
{
    use Notifiable;
    use HasFactory;
    use NotifiableNotificationHistory;
}
