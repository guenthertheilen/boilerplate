<?php

namespace App\Http\Controllers;

use App\Events\UserActivated;
use App\Models\User;

class UserActivationController extends Controller
{
    /**
     * Activate user
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($token)
    {
        $user = app(User::class)->where('activation_token', '=', $token)->firstOrFail();

        $user->update([
            'active' => 1
        ]);

        event(UserActivated::class);

        return redirect(route('login'));
    }
}
