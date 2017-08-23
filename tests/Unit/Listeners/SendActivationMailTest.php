<?php

namespace Tests\Unit\Listeners;

use App\Events\UserCreated;
use App\Listeners\SendActivationMail;
use App\Mail\ActivateAccount;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendActivationMailTest extends TestCase
{
    /** @test */
    public function it_sends_activation_mail()
    {
        $user = factory(User::class)->make();
        $event = new SendActivationMail();
        $event->handle(new UserCreated($user));

        Mail::assertSent(ActivateAccount::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
