@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Employee Management</h4>

            <a href="{{ route('employees.create') }}"
               class="btn btn-primary">
                Add Employee
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($employees as $key => $employee)
                        <tr>
                            <td>{{ $employees->firstItem()+$key }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>
                                @if($employee->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('employees.edit',$employee->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('employees.destroy',$employee->id) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $employees->links() }}

        </div>
    </div>

</div>
@endsection