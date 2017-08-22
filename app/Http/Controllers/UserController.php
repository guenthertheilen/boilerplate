<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index')->with('users', $this->user->with('roles')->get());
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create')->with(['roles' => Role::all()]);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array_merge(
            $request->only(['name', 'email']),
            ['password' => '']
        );

        $user = $this->user->create($data);
        $user->roles()->syncWithoutDetaching($request->get('roles'));

        return redirect(route('user.index'));
    }

    /**
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with(['user' => $user, 'roles' => Role::all()]);
    }

    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        // TODO: Replace by route model binding in L5.5
        // route model binding does not work in 5.4 with WithoutMiddleware in tests
        $userToUpdate = $this->user->find($id);
        $userToUpdate->update($request->only(['name', 'email']));
        $userToUpdate->roles()->sync($request->get('roles'));

        return redirect(route('user.index'));
    }

    /**
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
