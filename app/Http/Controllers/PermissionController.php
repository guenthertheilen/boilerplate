<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PermissionController extends Controller
{
    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function index(): View
    {
        return view('permissions.index')->with('permissions', $this->permission->with('roles')->get());
    }

    public function create(): Response
    {
        //
    }

    public function store(Request $request): RedirectResponse
    {
        $this->permission->create($request->only(['name']));

        return redirect(route('permission.index'));
    }

    public function show(Permission $permission): Response
    {
        //
    }

    public function edit(Permission $permission): View
    {
        return view('permissions.edit')->with('permission', $permission);
    }

    public function update(Request $request, Permission $permission): Response
    {
        //
    }

    public function destroy(Permission $permission): Response
    {
        //
    }
}
