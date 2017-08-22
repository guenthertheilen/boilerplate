<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\ActivateAccount;
use Illuminate\Support\Facades\Mail;

class SendActivationMail
{
    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        Mail::to($event->user)->send(new ActivateAccount($event->user));
    }
}
