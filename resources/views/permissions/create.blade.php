@extends('layouts.app')
@section('title','Permission Create')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4>Create Permission</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Group</label>

                    <input type="text"
                           name="group"
                           class="form-control"
                           placeholder="Ex: Employee"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permission Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Ex: employee.create"
                           required>

                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button class="btn btn-success">
                    Save Permission
                </button>

                <a href="{{ route('permissions.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>
    </div>

</div>
@endsection