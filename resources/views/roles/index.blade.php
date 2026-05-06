@extends('layouts.app')
@section('title','Role Management')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Role Management</h4>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                Add Role
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($roles as $key => $role)
                        <tr>
                            <td>{{ $roles->firstItem() + $key }}</td>
                            <td>{{ ucfirst($role->name) }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-success mb-1">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('roles.edit',$role->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('roles.destroy',$role->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this role?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                No role found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $roles->links() }}

        </div>
    </div>

</div>
@endsection