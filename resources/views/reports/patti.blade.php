@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.patti') }}">
                <div class="row g-2">

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="date"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="baji_id" class="form-select">
                            <option value="">All Baji</option>
                            @foreach($bajis as $baji)
                                <option value="{{ $baji->id }}"
                                    @selected(request('baji_id')==$baji->id)>
                                    {{ $baji->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="agent_id" class="form-select">
                            <option value="">All Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}"
                                    @selected(request('agent_id')==$agent->id)>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="text"
                               name="game_number"
                               value="{{ request('game_number') }}"
                               placeholder="Search Patti"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="number"
                               name="min_amount"
                               value="{{ request('min_amount') }}"
                               placeholder="Min Amount"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="number"
                               name="max_amount"
                               value="{{ request('max_amount') }}"
                               placeholder="Max Amount"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="sort" class="form-select">
                            <option value="">Most Played</option>
                            <option value="number_asc" @selected(request('sort')=='number_asc')>Number ASC</option>
                            <option value="number_desc" @selected(request('sort')=='number_desc')>Number DESC</option>
                            <option value="amount_asc" @selected(request('sort')=='amount_asc')>Amount ASC</option>
                            <option value="amount_desc" @selected(request('sort')=='amount_desc')>Amount DESC</option>
                            <option value="entry_asc" @selected(request('sort')=='entry_asc')>Entry ASC</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('reports.patti') }}"
                           class="btn btn-secondary w-100">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>


    {{-- SUMMARY --}}
    <div class="row g-3 mb-3">

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Total Amount</small>
                    <h3>₹{{ number_format($summary['total_amount'],2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Total Entries</small>
                    <h3>{{ $summary['total_entry'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Unique Patti</small>
                    <h3>{{ $summary['unique_count'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Highest Patti</small>
                    <h3>{{ $summary['highest']->game_number ?? '-' }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card mt-3">
        <div class="card-header">
            Patti Starting Number Summary
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Starting Digit</th>
                        <th>Total Entry</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($digitReport as $row)
                        <tr>
                            <td>{{ $row['digit'] }}</td>
                            <td>{{ $row['total_entry'] }}</td>
                            <td>₹{{ number_format($row['total_amount'],2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Patti Report</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Patti</th>
                        <th>Total Entry</th>
                        <th>Total Amount</th>
                        <th>Unique Agents</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                        <tr>
                            <td>
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    {{ $row->game_number }}
                                </span>
                            </td>
                            <td>{{ $row->total_entry }}</td>
                            <td class="fw-bold">
                                ₹{{ number_format($row->total_amount,2) }}
                            </td>
                            <td>{{ $row->total_agents }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                No Data Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection