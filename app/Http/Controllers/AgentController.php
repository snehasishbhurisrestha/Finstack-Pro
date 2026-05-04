<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AgentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Agent Show', only: ['index']),
            new Middleware('permission:Agent Create', only: ['create','store']),
            new Middleware('permission:Agent Edit', only: ['edit','update']),
            new Middleware('permission:Agent Delete', only: ['destroy'])
        ];
    }

    public function index()
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $agents = Agent::with('Employee')
                ->latest()
                ->paginate(15);
        } else {
            $agents = Agent::with('Employee')
                ->where('employee_id', auth()->id())
                ->latest()
                ->paginate(15);
        }

        return view('agents.index', compact('agents'));
    }

    public function create()
    {
        $employees = User::role('Employee')
            ->where('status',1)
            ->orderBy('name')
            ->get();

        return view('agents.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'notes' => 'nullable',
        ]);

        Agent::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'status' => 1,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('agents.index')
            ->with('success','Agent created successfully');
    }

    public function edit($id)
    {
        $agent = Agent::findOrFail($id);

        $employees = User::role('employee')
            ->where('status',1)
            ->orderBy('name')
            ->get();

        return view(
            'agents.edit',
            compact('agent','employees')
        );
    }

    public function update(Request $request,$id)
    {
        $agent = Agent::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'notes' => 'nullable',
        ]);

        $agent->update([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('agents.index')
            ->with('success','Agent updated successfully');
    }

    public function destroy($id)
    {
        Agent::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Agent deleted successfully'
        );
    }
}