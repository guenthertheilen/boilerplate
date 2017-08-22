<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\ActivateAccount;
use Illuminate\Support\Facades\Mail;

class SendActivationMail
{
    /**
     * Create the event listener.
     *
     * @return void
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
        Mail::to($event->user)->send(new ActivateAccount($event->user));
    }
}
