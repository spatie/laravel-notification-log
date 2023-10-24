<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_log_items', function (Blueprint $table) {
            $table->id();
            $table->string('notification_type');
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->string('notifiable_type')->nullable();
            $table->string('channel');
            $table->string('fingerprint')->nullable();
            $table->json('extra')->nullable();
            $table->json('anonymous_notifiable_properties')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index(['created_at']);
        });
    }
};
