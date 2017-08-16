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
        $user = app(User::class)->where('activation_token', '=', $token)->firstOrFail();

    	if ($user->hasNoPassword())
	    {
		    return redirect(route('password.create', $token));
	    }

        $user->update([
		    'active' => 1,
		    'activation_token' => ''
        ]);

        return redirect(route('login'));
    }
}
