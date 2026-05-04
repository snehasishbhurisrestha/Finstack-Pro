<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Employee Show', only: ['index']),
            new Middleware('permission:Employee Create', only: ['create','store']),
            new Middleware('permission:Employee Edit', only: ['edit','update']),
            new Middleware('permission:Employee Delete', only: ['destroy'])
        ];
    }

    public function index()
    {
        $employees = User::role('Employee')
            ->latest()
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('employees.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'address' => 'nullable',
            'password' => 'required|min:6',
            'permissions' => 'nullable|array'
        ]);

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 1,
            'password' => Hash::make($request->password),
        ]);

        $employee->assignRole('Employee');

        if ($request->permissions) {
            $employee->syncPermissions($request->permissions);
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee created successfully');
    }

    public function edit($id)
    {
        $employee = User::findOrFail($id);
        $permissions = Permission::orderBy('name')->get();
        $userPermissions = $employee->getPermissionNames()->toArray();

        return view(
            'employees.edit',
            compact(
                'employee',
                'permissions',
                'userPermissions'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required',
            'address' => 'nullable',
            'permissions' => 'nullable|array'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        $employee->syncPermissions($request->permissions ?? []);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully');
    }

    public function destroy($id)
    {
        $employee = User::findOrFail($id);

        $employee->delete();

        return back()->with(
            'success',
            'Employee deleted successfully'
        );
    }
}