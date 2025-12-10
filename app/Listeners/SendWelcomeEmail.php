<?php

namespace App\Listeners;

use App\Notifications\WelcomeNewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendWelcomeEmail
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
    public function handle(Registered $event): void
    {
        /** @var \App\Models\User $newUser*/
        $newUser = $event->user;

        Notification::send($newUser, (new WelcomeNewUserNotification)->delay(now()->addMinutes(1)));
    }
}
