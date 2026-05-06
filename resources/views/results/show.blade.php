@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4>Result Details</h4>

        <a href="{{ route('results.history') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small>Patti</small>
                    <h2>{{ $result->patti }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small>Single</small>
                    <h2 class="text-success">{{ $result->single }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small>Total Liability</small>
                    <h4 class="text-danger">
                        ₹{{ number_format($result->total_liability,2) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small>Profit / Loss</small>
                    <h4 class="{{ $result->profit_loss >= 0 ? 'text-success':'text-danger' }}">
                        ₹{{ number_format($result->profit_loss,2) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Agent Summary --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">Agent Wise Winning</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Total Entry</th>
                        <th>Total Win</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agentSummary as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->total_entry }}</td>
                            <td class="text-danger fw-bold">
                                ₹{{ number_format($row->total_amount,2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Winning Entries --}}
    <div class="card shadow-sm border-0">
        <div class="card-header">Winning Entries</div>

        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Agent</th>
                        <th>Number</th>
                        <th>Amount</th>
                        <th>Entry By</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->created_at->format('d M h:i A') }}</td>
                            <td>{{ $entry->agent->name }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $entry->game_number }}
                                </span>
                            </td>
                            <td>₹{{ $entry->amount }}</td>
                            <td>{{ $entry->employee->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection