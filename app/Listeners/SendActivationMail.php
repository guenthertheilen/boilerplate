<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\ActivateAccount;
use Illuminate\Mail\Mailer;

class SendActivationMail
{
    public function handle(UserCreated $event)
    {
        // TODO: Constructor injection?
        app(Mailer::class)
            ->to($event->user)
            ->send(new ActivateAccount($event->user));
    }
}
