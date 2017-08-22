<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(): View
    {
        return view('users.index')->with('users', $this->user->with('roles')->get());
    }

    public function create(): View
    {
        return view('users.create')->with(['roles' => Role::all()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = array_merge(
            $request->only(['name', 'email']),
            ['password' => '']
        );

        $user = $this->user->create($data);
        $user->roles()->syncWithoutDetaching($request->get('roles'));

        return redirect(route('user.index'));
    }

    public function show(int $id): Response
    {
        //
    }

    public function edit(User $user): View
    {
        return view('users.edit')->with(['user' => $user, 'roles' => Role::all()]);
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        // TODO: Replace by route model binding in L5.5
        // route model binding does not work in 5.4 with WithoutMiddleware in tests
        $userToUpdate = $this->user->find($id);
        $userToUpdate->update($request->only(['name', 'email']));
        $userToUpdate->roles()->sync($request->get('roles'));

        return redirect(route('user.index'));
    }

    public function destroy(int $id): Response
    {
        //
    }
}
