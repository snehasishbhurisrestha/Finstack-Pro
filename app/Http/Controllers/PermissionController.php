<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Permission Show', only: ['index']),
            new Middleware('permission:Permission Create', only: ['create','store']),
            new Middleware('permission:Permission Edit', only: ['edit','update']),
            new Middleware('permission:Permission Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $permissions = Permission::orderBy('group')
            ->orderBy('name')
            ->paginate(20);

        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'group' => 'required|string|max:255',
            'name'  => 'required|unique:permissions,name',
        ]);

        $permission = new Permission();
        $permission->group = $request->group;
        $permission->name = $request->name;
        $permission->guard_name = 'web';
        $permission->save();

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'group' => 'required|string|max:255',
            'name'  => 'required|unique:permissions,name,' . $id,
        ]);

        $permission->group = $request->group;
        $permission->name = $request->name;
        $permission->update();

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        if ($permission->roles()->count() > 0) {
            return back()->with(
                'error',
                'Permission is assigned to role. Remove it first.'
            );
        }

        $permission->delete();

        return back()->with(
            'success',
            'Permission deleted successfully'
        );
    }
}