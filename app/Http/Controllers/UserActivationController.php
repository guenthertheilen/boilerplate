<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserActivationController extends Controller
{
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param String $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(String $token)
    {
        $inactiveUser = $this->user->where('activation_token', '=', $token)->firstOrFail();

        if ($inactiveUser->hasNoPassword()) {
            return redirect(route('password.create', $token));
        }

        $inactiveUser->activate();

        return redirect(route('login'));
    }
}
