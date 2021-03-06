<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permissions.index', ['permissions' => Permission::with('roles')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePermissionRequest $request)
    {
        Permission::create($request->only(['name']));

        return redirect(route('permission.index'))
            ->with('flash-success', __('Permission was created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', ['permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->only(['name']));

        return redirect(route('permission.index'))
            ->with('flash-success', __('Permission was updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
