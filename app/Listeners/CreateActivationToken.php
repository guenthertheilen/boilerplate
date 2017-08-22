<?php

namespace App\Listeners;

use App\Events\UserCreated;

class CreateActivationToken
{
    public function handle(UserCreated $event)
    {
        $event->user->createActivationToken();
    }
}
