<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserActivationController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function update(string $token): RedirectResponse
    {
        $inactiveUser = $this->user->where('activation_token', '=', $token)->firstOrFail();

        if ($inactiveUser->hasNoPassword()) {
            return redirect(route('password.create', $token));
        }

        $inactiveUser->activate();

        return redirect(route('login'));
    }
}
