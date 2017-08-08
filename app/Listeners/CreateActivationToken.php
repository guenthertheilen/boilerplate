<?php

namespace App\Listeners;

use App\Events\UserCreated;

class CreateActivationToken
{
    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->createActivationToken();
    }
}
