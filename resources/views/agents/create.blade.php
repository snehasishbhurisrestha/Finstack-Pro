@extends('layouts.app')
@section('title','Agent Create')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Create Agent</h4>
        </div>

        <div class="card-body">

        <form method="POST" action="{{ route('agents.store') }}">
            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Assign Employee</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email"
       name="email"
       class="form-control">
</div>

<div class="col-md-12 mb-3">
<label>Address</label>
<textarea name="address"
          class="form-control"></textarea>
</div>

<div class="col-md-12 mb-3">
<label>Notes</label>
<textarea name="notes"
          class="form-control"></textarea>
</div>

</div>

<button class="btn btn-success">
    Save Agent
</button>

</form>

</div>
</div>
</div>
@endsection