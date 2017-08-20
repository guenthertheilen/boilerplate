<?php

namespace Tests\Unit\Listeners;

use App\Events\UserCreated;
use App\Listeners\SendActivationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendActivationMailTest extends TestCase
{
    /** @test */
    public function itSendsActivationMail()
    {
        Mail::fake();
        
        $user = factory(User::class)->make();
        $event = new SendActivationMail();
        $event->handle(new UserCreated($user));

        Mail::assertSent(ActivateAccount::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
                                               $mail->hasCc('...') &&
                                                                  $mail->hasBcc('...');
        });
    }
}
