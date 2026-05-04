@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="card">
<div class="card-header">
    <h4>Edit Employee</h4>
</div>

<div class="card-body">

<form action="{{ route('employees.update',$employee->id) }}"
      method="POST">
@csrf
@method('PUT')

<div class="row">

<div class="col-md-6 mb-3">
<label>Name</label>
<input type="text"
       name="name"
       value="{{ $employee->name }}"
       class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email"
       name="email"
       value="{{ $employee->email }}"
       class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Phone</label>
<input type="text"
       name="phone"
       value="{{ $employee->phone }}"
       class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>New Password</label>
<input type="password"
       name="password"
       class="form-control">
</div>

<div class="col-md-12 mb-3">
<label>Address</label>
<textarea name="address"
          class="form-control">{{ $employee->address }}</textarea>
</div>

<div class="col-md-6 mb-3">
<label>Status</label>
<select name="status" class="form-control">
    <option value="1" {{ $employee->status ? 'selected':'' }}>Active</option>
    <option value="0" {{ !$employee->status ? 'selected':'' }}>Inactive</option>
</select>
</div>

<div class="col-md-12 mb-3">
<label>Permissions</label>

<div class="row">
@foreach($permissions as $permission)
<div class="col-md-3">
<label>
<input type="checkbox"
       name="permissions[]"
       value="{{ $permission->name }}"
       {{ in_array($permission->name,$userPermissions) ? 'checked':'' }}>
{{ $permission->name }}
</label>
</div>
@endforeach
</div>

</div>

</div>

<button class="btn btn-primary">Update Employee</button>

</form>

</div>
</div>
</div>
@endsection