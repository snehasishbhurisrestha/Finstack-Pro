@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    {{-- TOP --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm"
                 style="border-radius:18px;background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;">
                <div class="card-body text-center py-4">
                    <div style="font-size:14px;opacity:.9;">Today's Entries</div>
                    <h1 class="mb-0">{{ $todayEntries }}</h1>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm"
                 style="border-radius:18px;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;">
                <div class="card-body text-center py-4">
                    <div style="font-size:14px;opacity:.9;">Today's Amount</div>
                    <h1 class="mb-0">
                        ₹{{ number_format($todayAmount,2) }}
                    </h1>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <a href="{{ route('game-entry.index') }}"
               class="card border-0 shadow-sm text-decoration-none"
               style="border-radius:18px;background:linear-gradient(135deg,#7c3aed,#9333ea);color:#fff;">
                <div class="card-body text-center py-4">
                    <div style="font-size:14px;opacity:.9;">Quick Action</div>
                    <h3 class="mb-0">+ NEW ENTRY</h3>
                </div>
            </a>
        </div>

    </div>


    {{-- CHART + TABLE --}}
    <div class="row g-4">

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm"
                 style="border-radius:18px;">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">My 7 Day Trend</h5>
                </div>
                <div class="card-body">
                    <div id="employeeTrend"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm"
                 style="border-radius:18px;">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Recent Entries</h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Agent</th>
                                <th>Baji</th>
                                <th>Number</th>
                                <th>Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($recentEntries as $entry)
                                <tr>
                                    <td>
                                        {{ $entry->created_at->format('d M h:i A') }}
                                    </td>
                                    <td>
                                        {{ $entry->agent->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $entry->baji->name ?? '-' }}
                                    </td>
                                    <td>
                                        <b>{{ $entry->game_number }}</b>
                                    </td>
                                    <td>
                                        ₹{{ number_format($entry->amount,2) }}
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
@endsection


@section('script')
<script>
new ApexCharts(document.querySelector("#employeeTrend"), {
    chart:{
        type:'area',
        height:320,
        toolbar:{show:false}
    },
    stroke:{
        curve:'smooth',
        width:3
    },
    dataLabels:{enabled:false},
    series:[{
        name:'Amount',
        data:@json($weekly->pluck('total')->values())
    }],
    xaxis:{
        categories:@json($weekly->pluck('day')->values())
    },
    fill:{
        type:'gradient',
        gradient:{
            opacityFrom:.6,
            opacityTo:.08
        }
    }
}).render();
</script>
@endsection