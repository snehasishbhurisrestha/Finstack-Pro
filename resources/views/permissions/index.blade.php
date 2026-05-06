@extends('layouts.app')
@section('title','Permission Management')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Permission Management</h4>

            <a href="{{ route('permissions.create') }}"
               class="btn btn-primary">
                Add Permission
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="80">#</th>
                        <th width="180">Group</th>
                        <th>Permission Name</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($permissions as $key => $permission)
                        <tr>
                            <td>{{ $permissions->firstItem() + $key }}</td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $permission->group }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $permission->name }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('permissions.edit',$permission->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('permissions.destroy',$permission->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this permission?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="text-center text-muted">
                                No permission found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $permissions->links() }}
            </div>

        </div>
    </div>

</div>
@endsection