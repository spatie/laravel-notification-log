<?php

namespace Spatie\NotificationLog\Tests\TestSupport\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\NotificationLog\Tests\TestSupport\Models\UuidUser;

class UuidUserFactory extends Factory
{
    public $model = UuidUser::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
}

