@extends('layouts.app')

@section('title', 'Result History')

@section('content')
<div class="container-fluid">

    {{-- PAGE TITLE --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Result History</h4>

        <a href="{{ route('results.history') }}" class="btn btn-secondary">
            Reset Filter
        </a>
    </div>

    {{-- FILTER CARD --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.history') }}">
                <div class="row g-2">

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="date"
                               name="date_from"
                               value="{{ old('date_from', request('date_from')) }}"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="date"
                               name="date_to"
                               value="{{ old('date_to', request('date_to')) }}"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="baji_id" class="form-select">
                            <option value="">All Baji</option>
                            @foreach($bajis as $baji)
                                <option value="{{ $baji->id }}"
                                    {{ old('baji_id', request('baji_id')) == $baji->id ? 'selected' : '' }}>
                                    {{ $baji->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-1 col-md-4 col-6">
                        <input type="text"
                               name="patti"
                               value="{{ old('patti', request('patti')) }}"
                               placeholder="Patti"
                               class="form-control">
                    </div>

                    <div class="col-lg-1 col-md-4 col-6">
                        <input type="text"
                               name="single"
                               value="{{ old('single', request('single')) }}"
                               placeholder="Single"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="number"
                               name="min_liability"
                               value="{{ old('min_liability', request('min_liability')) }}"
                               placeholder="Min Liability"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="number"
                               name="max_liability"
                               value="{{ old('max_liability', request('max_liability')) }}"
                               placeholder="Max Liability"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="sort" class="form-select">
                            <option value="">Latest</option>
                            <option value="profit_high"
                                {{ old('sort', request('sort')) == 'profit_high' ? 'selected' : '' }}>
                                Profit High
                            </option>
                            <option value="profit_low"
                                {{ old('sort', request('sort')) == 'profit_low' ? 'selected' : '' }}>
                                Profit Low
                            </option>
                            <option value="liability_high"
                                {{ old('sort', request('sort')) == 'liability_high' ? 'selected' : '' }}>
                                Liability High
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('results.history') }}"
                           class="btn btn-secondary w-100">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Total Results</small>
                    <h4 class="mb-0">{{ $results->total() }}</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Total Liability</small>
                    <h4 class="mb-0 text-danger">
                        ₹{{ number_format($results->sum('total_liability'),2) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Total Collection</small>
                    <h4 class="mb-0 text-primary">
                        ₹{{ number_format($results->sum('total_collection'),2) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Total Profit/Loss</small>
                    <h4 class="mb-0 {{ $results->sum('profit_loss') >= 0 ? 'text-success' : 'text-danger' }}">
                        ₹{{ number_format($results->sum('profit_loss'),2) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Result Details</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Baji</th>
                        <th>Patti</th>
                        <th>Single</th>
                        <th>Patti Win</th>
                        <th>Single Win</th>
                        <th>CP Win</th>
                        <th>Total Entries</th>
                        <th>Total Collection</th>
                        <th>Total Liability</th>
                        <th>Profit/Loss</th>
                        <th>Declared By</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($results as $row)
                        <tr onclick="window.location='{{ route('results.show',$row->id) }}'" style="cursor:pointer">
                            <td>
                                {{ $row->result_date->format('d M Y') }}
                            </td>

                            <td>
                                {{ $row->baji->name ?? '-' }}
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $row->patti }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $row->single }}
                                </span>
                            </td>

                            <td>
                                ₹{{ number_format($row->patti_win_amount,2) }}
                                <br>
                                <small class="text-muted">
                                    {{ $row->patti_win_count }} entries
                                </small>
                            </td>

                            <td>
                                ₹{{ number_format($row->single_win_amount,2) }}
                                <br>
                                <small class="text-muted">
                                    {{ $row->single_win_count }} entries
                                </small>
                            </td>

                            <td>
                                ₹{{ number_format($row->cp_win_amount,2) }}
                                <br>
                                <small class="text-muted">
                                    {{ $row->cp_win_count }} entries
                                </small>
                            </td>

                            <td>
                                {{ $row->total_entries }}
                            </td>

                            <td class="text-primary fw-bold">
                                ₹{{ number_format($row->total_collection,2) }}
                            </td>

                            <td class="text-danger fw-bold">
                                ₹{{ number_format($row->total_liability,2) }}
                            </td>

                            <td class="fw-bold {{ $row->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($row->profit_loss,2) }}
                            </td>

                            <td>
                                {{ $row->user->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                No result found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($results->hasPages())
            <div class="card-footer bg-white">
                {{ $results->links() }}
            </div>
        @endif
    </div>

</div>
@endsection