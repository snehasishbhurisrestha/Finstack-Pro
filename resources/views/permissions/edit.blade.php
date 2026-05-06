@extends('layouts.app')
@section('title','Permission Edit')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4>Edit Permission</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('permissions.update',$permission->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">
                        Group
                    </label>

                    <input type="text"
                           name="group"
                           class="form-control"
                           value="{{ old('group', $permission->group) }}"
                           placeholder="Ex: Employee"
                           required>

                    @error('group')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Permission Name
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $permission->name) }}"
                           placeholder="Ex: employee.create"
                           required>

                    @error('name')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <button class="btn btn-primary">
                    Update Permission
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