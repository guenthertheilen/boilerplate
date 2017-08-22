<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UserPasswordController extends Controller
{

    public function create(string $token): View
    {
        return view('passwords.create')->with('token', $token);
    }

    public function store(CreatePasswordRequest $request): RedirectResponse
    {
        app(User::class)->where([
            ['activation_token', '=', $request->get('activation_token')],
            ['email', '=', $request->get('email')]
        ])->firstOrFail()
            ->update(['password' => bcrypt($request->get('password'))]);

        return redirect(route('user.activate', $request->get('activation_token')));
    }

    public function edit(int $id): Response
    {
        //
    }

    public function update(Request $request, int $id): Response
    {
        //
    }
}
