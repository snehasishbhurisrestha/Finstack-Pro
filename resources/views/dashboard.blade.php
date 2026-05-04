@extends('layouts.app')

@section('content')

{{-- <div class="row">


    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle me-3">
                    <i class="ph ph-briefcase f-s-22 text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0">{{ $programmes }}</h4>
                    <p class="text-muted mb-0">Programmes</p>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle me-3">
                    <i class="ph ph-users f-s-22 text-success"></i>
                </div>
                <div>
                    <h4 class="mb-0">{{ $players }}</h4>
                    <p class="text-muted mb-0">Players</p>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle me-3">
                    <i class="ph ph-user-circle f-s-22 text-warning"></i>
                </div>
                <div>
                    <h4 class="mb-0">{{ $coaches }}</h4>
                    <p class="text-muted mb-0">Coaches</p>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="bg-light-danger h-50 w-50 d-flex-center rounded-circle me-3">
                    <i class="ph ph-calendar f-s-22 text-danger"></i>
                </div>
                <div>
                    <h4 class="mb-0">{{ $sessions }}</h4>
                    <p class="text-muted mb-0">Sessions</p>
                </div>
            </div>
        </div>
    </div>



    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5>Upcoming Sessions</h5>
            </div>

            <div class="card-body p-0">

                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Programme</th>
                        <th>Group</th>
                        <th>Date</th>
                        <th>Location</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($upcomingSessions as $session)

                    <tr>
                        <td>{{ $session->programme->name }}</td>
                        <td>{{ $session->group->name }}</td>
                        <td>{{ $session->date }}</td>
                        <td>{{ $session->location }}</td>
                    </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5>Pending Child Requests</h5>
            </div>

            <div class="card-body">

                @foreach($requests as $request)

                <div class="d-flex align-items-center mb-3">

                    <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle">
                        <i class="ph ph-user"></i>
                    </div>

                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-0">{{ $request->child_name }}</h6>
                        <small class="text-muted">
                            {{ $request->programme->name }}
                        </small>
                    </div>

                    <span class="badge bg-warning">Pending</span>

                </div>

                @endforeach

            </div>
        </div>
    </div>



    <div class="col-lg-6">

        <div class="card">

            <div class="card-header">
                <h5>Recent Alerts</h5>
            </div>

            <div class="card-body">

                @foreach($alerts as $alert)

                <div class="d-flex mb-3">

                    <div class="bg-light-danger h-40 w-40 d-flex-center rounded-circle me-3">
                        <i class="ph ph-bell"></i>
                    </div>

                    <div>
                        <h6 class="mb-0">{{ $alert->title }}</h6>
                        <small class="text-muted">{{ $alert->message }}</small>
                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>


    <div class="col-lg-6">

        <div class="card">

            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>

            <div class="card-body">

                <div class="row g-2">

                    <div class="col-md-4">
                        <a href="javascript:void(0);" class="btn btn-primary w-100">
                            Create Programme
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="javascript:void(0);" class="btn btn-outline-primary w-100">
                            Add Session
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="javascript:void(0);" class="btn btn-outline-dark w-100">
                            Manage People
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div> --}}

@endsection
