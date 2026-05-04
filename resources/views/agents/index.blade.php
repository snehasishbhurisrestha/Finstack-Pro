@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Agent Management</h4>

            <a href="{{ route('agents.create') }}"
            class="btn btn-primary">
                Add Agent
            </a>
        </div>

        <div class="card-body">

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Employee</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agents as $key=>$agent)
                <tr>
                    <td>{{ $agents->firstItem()+$key }}</td>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->employee->name }}</td>
                    <td>{{ $agent->phone }}</td>
                    <td>
                        @if($agent->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('agents.edit',$agent->id) }}"
                        class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('agents.destroy',$agent->id) }}"
                            method="POST"
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

        {{ $agents->links() }}

        </div>
    </div>

</div>
@endsection