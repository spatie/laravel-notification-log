<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Spatie\NotificationLog\Models\Concerns\HasNotifiableHistory;

class UuidUser extends BaseUser
{
    use HasFactory;
    use HasNotifiableHistory;
    use HasUuids;
    use Notifiable;

    protected $keyType = 'string';

    public $incrementing = false;
}
