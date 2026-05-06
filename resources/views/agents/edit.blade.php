@extends('layouts.app')
@section('title','Agent Edit')
@section('content')
<div class="container-fluid">
<div class="card">
<div class="card-header">
    <h4>Edit Agent</h4>
</div>

<div class="card-body">

<form method="POST"
      action="{{ route('agents.update',$agent->id) }}">
@csrf
@method('PUT')

<div class="row">

<div class="col-md-6 mb-3">
<label>Assign Employee</label>
<select name="employee_id" class="form-control" required>
    @foreach($employees as $employee)
        <option value="{{ $employee->id }}"
            {{ $agent->employee_id==$employee->id ? 'selected':'' }}>
            {{ $employee->name }}
        </option>
    @endforeach
</select>
</div>

<div class="col-md-6 mb-3">
<label>Name</label>
<input type="text"
       name="name"
       value="{{ $agent->name }}"
       class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Phone</label>
<input type="text"
       name="phone"
       value="{{ $agent->phone }}"
       class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email"
       name="email"
       value="{{ $agent->email }}"
       class="form-control">
</div>

<div class="col-md-12 mb-3">
<label>Address</label>
<textarea name="address"
          class="form-control">{{ $agent->address }}</textarea>
</div>

<div class="col-md-12 mb-3">
<label>Notes</label>
<textarea name="notes"
          class="form-control">{{ $agent->notes }}</textarea>
</div>

<div class="col-md-6 mb-3">
<label>Status</label>
<select name="status" class="form-control">
    <option value="1" {{ $agent->status ? 'selected':'' }}>
        Active
    </option>
    <option value="0" {{ !$agent->status ? 'selected':'' }}>
        Inactive
    </option>
</select>
</div>

</div>

<button class="btn btn-primary">
    Update Agent
</button>

</form>

</div>
</div>
</div>
@endsection