@extends('layouts.app')
@section('title','Single Report')
@section('content')
<div class="container-fluid py-3">

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.single') }}">
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

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="agent_id" class="form-select">
                            <option value="">All Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}"
                                    {{ old('agent_id', request('agent_id')) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="number"
                            name="min_amount"
                            value="{{ old('min_amount', request('min_amount')) }}"
                            placeholder="Min Amount"
                            class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="number"
                            name="max_amount"
                            value="{{ old('max_amount', request('max_amount')) }}"
                            placeholder="Max Amount"
                            class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <select name="sort" class="form-select">
                            <option value="">Default</option>
                            <option value="amount_asc" {{ old('sort', request('sort')) == 'amount_asc' ? 'selected' : '' }}>Amount ASC</option>
                            <option value="amount_desc" {{ old('sort', request('sort')) == 'amount_desc' ? 'selected' : '' }}>Amount DESC</option>
                            <option value="entry_asc" {{ old('sort', request('sort')) == 'entry_asc' ? 'selected' : '' }}>Entry ASC</option>
                            <option value="entry_desc" {{ old('sort', request('sort')) == 'entry_desc' ? 'selected' : '' }}>Entry DESC</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('reports.single') }}"
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
                    <small>Highest Single</small>
                    <h3>{{ $summary['highest']->game_number ?? '-' }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small>Lowest Single</small>
                    <h3>{{ $summary['lowest']->game_number ?? '-' }}</h3>
                </div>
            </div>
        </div>

    </div>


    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Single Report</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Single</th>
                        <th>Total Entry</th>
                        <th>Total Amount</th>
                        <th>Unique Agents</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report as $row)
                        <tr class="single-row" data-number="{{ $row->game_number }}" style="cursor:pointer">
                            <td>
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    {{ $row->game_number }}
                                </span>
                            </td>
                            <td>{{ $row->total_entry }}</td>
                            <td class="fw-bold">
                                ₹{{ number_format($row->total_amount,2) }}
                            </td>
                            <td>{{ $row->total_agents }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="agentCanvas" style="width:500px;">

        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">
                Agent Details :
                <span id="selectedNumber"
                    class="badge bg-primary"></span>
            </h5>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body p-0">

            <div id="agentLoader"
                class="text-center p-4 d-none">
                Loading...
            </div>

            <div id="agentBody"></div>

        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    $(document).on('click', '.single-row', function () {

        let number = $(this).data('number');

        $('#selectedNumber').text(number);
        $('#agentLoader').removeClass('d-none');
        $('#agentBody').html('');

        let canvas = new bootstrap.Offcanvas(
            document.getElementById('agentCanvas')
        );

        canvas.show();

        $.get("{{ route('reports.single.agent.details') }}", {
            number: number,
            date_from: "{{ request('date_from') }}",
            date_to: "{{ request('date_to') }}",
            baji_id: "{{ request('baji_id') }}",
            agent_id: "{{ request('agent_id') }}",
            min_amount: "{{ request('min_amount') }}",
            max_amount: "{{ request('max_amount') }}"
        }, function(res){

            $('#agentLoader').addClass('d-none');

            let html = `
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Agent</th>
                            <th>Entry</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            let totalEntry = 0;
            let totalAmount = 0;

            if(res.length === 0){

                html += `
                    <tr>
                        <td colspan="3"
                            class="text-center text-muted py-4">
                            No data found
                        </td>
                    </tr>
                `;

            } else {

                res.forEach(row => {

                    totalEntry += Number(row.total_entry);
                    totalAmount += Number(row.total_amount);

                    html += `
                        <tr>
                            <td>${row.name}</td>
                            <td>${row.total_entry}</td>
                            <td class="fw-bold">
                                ₹${Number(row.total_amount).toFixed(2)}
                            </td>
                        </tr>
                    `;
                });

                /* footer total */
                html += `
                    <tr class="table-dark sticky-bottom">
                        <th>Total</th>
                        <th>${totalEntry}</th>
                        <th>₹${totalAmount.toFixed(2)}</th>
                    </tr>
                `;
            }

            html += `
                    </tbody>
                </table>
            `;

            $('#agentBody').html(html);

        });

    });
</script>
@endsection