<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CriticalTemperatureEvent;
use App\Notifications\CriticalTemperatureNotification;
use Illuminate\Support\Facades\Notification;


class SendCriticalTemperatureNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CriticalTemperatureEvent $event): void
    {
        $alert = $event->alert;

        $refrigerator = $alert->refrigerator;
        if (!$refrigerator || !$refrigerator->bloodBank) {
            return;
        }

        $users = $refrigerator->bloodBank->users()->get();

        Notification::send($users, new CriticalTemperatureNotification());
    }
}
