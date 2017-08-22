<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\ActivateAccount;
use Illuminate\Mail\Mailer;

class SendActivationMail
{
    private $mail;

    public function __construct($mail = null)
    {
        if ($mail === null) {
            $this->mail = app(Mailer::class);
        } else {
            $this->mail = $mail;
        }
    }

    public function handle(UserCreated $event)
    {
        $this->mail
            ->to($event->user)
            ->send(new ActivateAccount($event->user));
    }
}
