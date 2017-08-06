<?php

namespace App\Providers;

use App\Events\UserCreated;

class GeneratePasswordIfEmpty
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        if (empty($event->user->getAttribute('password'))) {
            $event->user->update(['password' => 'foo']);
        }
    }
}
