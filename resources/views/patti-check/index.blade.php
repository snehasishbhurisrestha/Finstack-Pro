@extends('layouts.app')
@section('title','Patti Check')
@section('content')
<div class="container-fluid py-3">

    <div class="text-end mb-2">
        <a href="{{ route('patti-check.create') }}"
            class="btn btn-primary">
            Add Patti
        </a>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('patti-check.index') }}">
                <div class="row g-2">

                    <div class="col-lg-2 col-md-4 col-6">
                        <input type="date"
                            name="date"
                            value="{{ old('date', request('date')) }}"
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
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('patti-check.index') }}"
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
                    <small class="text-muted">Total Amount</small>
                    <h3 class="mb-0">
                        ₹{{ number_format($summary['total_amount'],2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small class="text-muted">Total Entries</small>
                    <h3 class="mb-0">
                        {{ number_format($summary['total_entry']) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small class="text-muted">Unique Patti</small>
                    <h3 class="mb-0">
                        {{ number_format($summary['unique_count']) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small class="text-muted">Highest Patti</small>
                    <h3 class="mb-0">
                        {{ $summary['highest']->game_number ?? '-' }}
                    </h3>

                    @if(isset($summary['highest']))
                        <small class="text-success">
                            ₹{{ number_format($summary['highest']->total_amount,2) }}
                        </small>
                    @endif
                </div>
            </div>
        </div>

    </div>


    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Patti Chart</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">

                <thead>
                    <tr>
                        @for($single = 0; $single <= 9; $single++)
                            <th>{{ $single }}</th>
                        @endfor
                    </tr>
                </thead>

                <tbody>
                    @for($row = 0; $row < $maxRows; $row++)
                        <tr>
                            @for($single = 0; $single <= 9; $single++)

                                @php
                                    $item = $pattis[$single][$row] ?? null;
                                @endphp

                                @php
                                    $patti = $item?->patti;
                                    $amount = $amountData[$patti] ?? 0;
                                @endphp

                                <td class="{{ $amount > 0 ? 'bg-success text-white fw-bold patti-details' : '' }}" @if($amount > 0) data-patti="{{ $patti }}" @endif>

                                    @if($patti)

                                        @if($amount > 0)

                                            <a href="javascript:void(0)"
                                            class="text-white text-decoration-none patti-details"
                                            data-patti="{{ $patti }}">
                                                <div>{{ $patti }}</div>
                                                <small>₹{{ number_format($amount,2) }}</small>
                                            </a>

                                        @else

                                            <div>{{ $patti }}</div>

                                        @endif

                                    @endif

                                </td>

                            @endfor
                        </tr>
                    @endfor
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
    $(document).on('click', '.patti-details', function () {

    let patti = $(this).data('patti');

    $('#selectedNumber').text(patti);

    $('#agentLoader').removeClass('d-none');
    $('#agentBody').html('');

    let canvas = new bootstrap.Offcanvas(
        document.getElementById('agentCanvas')
    );

    canvas.show();

    $.get("{{ route('patti-check.details') }}", {

        patti: patti,
        baji_id: "{{ request('baji_id') }}",
        date: "{{ request('date') }}"

    }, function(res){

        $('#agentLoader').addClass('d-none');

        let totalEntry = 0;
        let totalAmount = 0;

        let html = `
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Entry</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
        `;

        if(res.length === 0){

            html += `
                <tr>
                    <td colspan="3" class="text-center">
                        No Data Found
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
                        <td>₹${Number(row.total_amount).toFixed(2)}</td>
                    </tr>
                `;
            });

            html += `
                <tr class="table-dark">
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