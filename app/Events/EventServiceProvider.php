<?php

namespace App\Events;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CriticalTemperatureEvent;
use App\Listeners\SendCriticalTemperatureNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CriticalTemperatureEvent::class => [
            SendCriticalTemperatureNotification::class,
        ],
    ];
}
