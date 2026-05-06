@extends('layouts.app')
@section('title','Employee Create')
@section('content')
<div class="container-fluid">
<div class="card">
<div class="card-header">
    <h4>Create Employee</h4>
</div>

<div class="card-body">

<form action="{{ route('employees.store') }}" method="POST">
@csrf

<div class="row">

<div class="col-md-6 mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="col-md-12 mb-3">
<label>Address</label>
<textarea name="address" class="form-control"></textarea>
</div>

<div class="col-md-12 mb-3">
<label>Permissions</label>

<div class="row">
@foreach($permissions as $permission)
<div class="col-md-3">
<label>
<input type="checkbox"
       name="permissions[]"
       value="{{ $permission->name }}">
{{ $permission->name }}
</label>
</div>
@endforeach
</div>

</div>

</div>

<button class="btn btn-success">Save Employee</button>

</form>

</div>
</div>
</div>
@endsection