@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Role</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('roles.update',$role->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                {{-- Role Name --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Role Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name',$role->name) }}"
                           class="form-control"
                           required>

                    @error('name')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- Permissions --}}
                <div class="mb-4">
                    <label class="fw-bold fs-5 mb-3 d-block">
                        Assign Permissions
                    </label>

                    @foreach($permissions as $group => $items)
                        <div class="card mb-3 border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <strong>{{ $group }}</strong>

                                <label class="mb-0">
                                    <input type="checkbox"
                                           class="group-check"
                                           data-group="{{ \Illuminate\Support\Str::slug($group) }}">
                                    Check All
                                </label>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    @foreach($items as $permission)
                                        <div class="col-lg-3 col-md-4 col-6 mb-2">
                                            <label class="form-check">
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       class="form-check-input permission-checkbox group-{{ \Illuminate\Support\Str::slug($group) }}"
                                                       @checked(in_array($permission->name,$rolePermissions))>

                                                <span class="form-check-label">
                                                    {{ $permission->name }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-primary">
                    Update Role
                </button>

                <a href="{{ route('roles.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // group check all
    document.querySelectorAll('.group-check').forEach(function(checkAll){

        let group = checkAll.dataset.group;
        let groupItems = document.querySelectorAll('.group-' + group);

        // auto check header if all checked
        let allChecked = [...groupItems].every(item => item.checked);
        checkAll.checked = allChecked;

        // click check all
        checkAll.addEventListener('change', function(){

            groupItems.forEach(function(box){
                box.checked = checkAll.checked;
            });

        });

        // update header dynamically
        groupItems.forEach(function(box){
            box.addEventListener('change', function(){

                let allNowChecked =
                    [...groupItems].every(item => item.checked);

                checkAll.checked = allNowChecked;

            });
        });

    });

});
</script>
@endsection