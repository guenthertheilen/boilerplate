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
        $user = User::where('activation_token', $token)->firstOrFail();

        if ($user->hasNoPassword()) {
            return redirect(route('password.create', $token));
        }

        $user->activate();

        return redirect(route('login'))
            ->with('flash-success', __('Your account was activated. Please log in now.'));
    }
}
