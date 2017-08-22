<?php

namespace App\Http\Controllers;

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
        $user = User::whereActivationToken($token)->firstOrFail();

        if ($user->hasNoPassword()) {
            return redirect(route('password.create', $token));
        }

        // TODO: Refactor into model
        $user->update([
            'active' => 1,
            'activation_token' => ''
        ]);

        return redirect(route('login'));
    }
}
