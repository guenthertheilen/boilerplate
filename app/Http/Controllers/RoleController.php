<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RoleController extends Controller
{
    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function index(): View
    {
        return view('roles.index')->with('roles', $this->role->all());
    }

    public function create(): Response
    {
        //
    }

    public function store(Request $request): RedirectResponse
    {
        $this->role->create($request->only(['name']));

        return redirect(route('role.index'));
    }

    public function show(int $id): Response
    {
        //
    }

    public function edit(int $id): Response
    {
        //
    }

    public function update(Request $request, int $id): Response
    {
        //
    }

    public function destroy(int $id): Response
    {
        //
    }
}
