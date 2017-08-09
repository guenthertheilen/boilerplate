<?php

namespace App\Listeners;

use App\Events\UserActivated;

class DeleteActivationToken
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
     * @param  UserActivated $event
     * @return void
     */
    public function handle(UserActivated $event)
    {
        //
    }
}
