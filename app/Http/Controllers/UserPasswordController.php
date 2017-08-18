<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserPasswordController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($token)
    {
        return view('passwords.create')->with('token', $token);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreatePasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePasswordRequest $request)
    {
        app(User::class)->where([
                ['activation_token', '=', $request->get('activation_token')],
                ['email', '=', $request->get('email')]
            ])->firstOrFail()
           ->update(['password' => bcrypt($request->get('password'))]);

        return redirect(route('user.activate', $request->get('activation_token')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
