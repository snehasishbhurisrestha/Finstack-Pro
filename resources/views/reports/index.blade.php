@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Entry Report</h4>

            <a href="{{ route('game-entry.index') }}"
               class="btn btn-primary">
                Game Entry
            </a>
        </div>
    </div>
    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}">
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
                               placeholder="Game Number"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="sort" class="form-select">
                            <option value="">Latest</option>
                            <option value="amount_asc" @selected(request('sort')=='amount_asc')>Amount ASC</option>
                            <option value="amount_desc" @selected(request('sort')=='amount_desc')>Amount DESC</option>
                            <option value="number_asc" @selected(request('sort')=='number_asc')>Number ASC</option>
                            <option value="number_desc" @selected(request('sort')=='number_desc')>Number DESC</option>
                        </select>
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
                        <input type="number"
                               name="big_amount"
                               value="{{ $bigAmount }}"
                               placeholder="Big Bet"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('reports.index') }}"
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
                    <h6>Total Entries</h6>
                    <h3>{{ $summary->total_entries ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Amount</h6>
                    <h3>₹{{ number_format($summary->total_amount ?? 0,2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Unique Numbers</h6>
                    <h3>{{ $summary->unique_numbers ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Unique Agents</h6>
                    <h3>{{ $summary->unique_agents ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>


    {{-- TABS --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#entries">
                        All Entries
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#number">
                        Number Summary
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#agent">
                        Agent Summary
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#bigbet">
                        Big Bets
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                {{-- ALL --}}
                <div class="tab-pane fade show active" id="entries">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Agent</th>
                                    <th>Baji</th>
                                    <th>Number</th>
                                    <th>Amount</th>
                                    <th>Entry By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $entry)
                                    <tr>
                                        <td>{{ $entry->created_at->format('d M h:i A') }}</td>
                                        <td>{{ $entry->agent->name ?? '-' }}</td>
                                        <td>{{ $entry->baji->name ?? '-' }}</td>
                                        <td><b>{{ $entry->game_number }}</b></td>
                                        <td>₹{{ $entry->amount }}</td>
                                        <td>{{ $entry->employee->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- $entries->links() --}}
                </div>


                {{-- NUMBER SUMMARY --}}
                <div class="tab-pane fade" id="number">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Total Entry</th>
                                    <th>Total Amount</th>
                                    <th>Unique Agents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($numberSummary as $row)
                                    <tr>
                                        <td><b>{{ $row->game_number }}</b></td>
                                        <td>{{ $row->total_entry }}</td>
                                        <td>₹{{ number_format($row->total_amount,2) }}</td>
                                        <td>{{ $row->total_agents }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                {{-- AGENT SUMMARY --}}
                <div class="tab-pane fade" id="agent">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Agent</th>
                                    <th>Total Entry</th>
                                    <th>Total Amount</th>
                                    <th>Unique Numbers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agentSummary as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->total_entry }}</td>
                                        <td>₹{{ number_format($row->total_amount,2) }}</td>
                                        <td>{{ $row->total_numbers }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                {{-- BIG BET --}}
                <div class="tab-pane fade" id="bigbet">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Agent</th>
                                    <th>Number</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bigBets as $bet)
                                    <tr>
                                        <td>{{ $bet->created_at->format('d M h:i A') }}</td>
                                        <td>{{ $bet->agent->name ?? '-' }}</td>
                                        <td><b>{{ $bet->game_number }}</b></td>
                                        <td class="text-danger fw-bold">
                                            ₹{{ $bet->amount }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection