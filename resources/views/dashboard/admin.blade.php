@extends('layouts.app')

@section('title','Dashboard')

@section('style')
<style>
    .dash-card{
        border:none;
        border-radius:18px;
        overflow:hidden;
        transition:.25s;
        cursor:pointer;
        text-decoration:none;
        color:inherit;
        display:block;
    }

    .dash-card:hover{
        transform:translateY(-4px);
        box-shadow:0 20px 40px rgba(0,0,0,.08);
        color:inherit;
    }

    .dash-icon{
        width:56px;
        height:56px;
        border-radius:14px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:rgba(255,255,255,.25);
    }

    .chart-card{
        border:none;
        border-radius:18px;
    }

    .filter-box{
        border:none;
        border-radius:18px;
    }

    .value{
        font-size:28px;
        font-weight:700;
    }

    .title-sm{
        opacity:.9;
        font-size:14px;
    }
</style>
@endsection


@section('content')
<div class="container-fluid py-3">

    {{-- FILTER --}}
    <div class="card filter-box shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="row g-2">

                    <div class="col-lg-3 col-md-6">
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="form-control">
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <input type="date"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-6">
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

                    <div class="col-lg-2 col-md-6">
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

                    <div class="col-lg-1 col-6">
                        <button class="btn btn-primary">
                            Go
                        </button>
                    </div>

                    <div class="col-lg-1 col-6">
                        <a href="{{ route('dashboard') }}"
                           class="btn btn-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>


    {{-- KPI --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-6">
            <a href="{{ route('reports.index') }}"
               class="card dash-card shadow-sm"
               style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="title-sm">Today's Collection</div>
                        <div class="value">₹{{ number_format($todayCollection,2) }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-6">
            <a href="{{ route('reports.index') }}"
               class="card dash-card shadow-sm"
               style="background:linear-gradient(135deg,#0891b2,#06b6d4);color:#fff;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="title-sm">Total Entries</div>
                        <div class="value">{{ number_format($todayEntries) }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-6">
            <a href="{{ route('reports.single') }}"
               class="card dash-card shadow-sm"
               style="background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;">
                <div class="card-body text-center">
                    <div class="title-sm">Single</div>
                    <div class="value">₹{{ number_format($singleCollection,2) }}</div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-6">
            <a href="{{ route('reports.patti') }}"
               class="card dash-card shadow-sm"
               style="background:linear-gradient(135deg,#ea580c,#f97316);color:#fff;">
                <div class="card-body text-center">
                    <div class="title-sm">Patti</div>
                    <div class="value">₹{{ number_format($pattiCollection,2) }}</div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-6">
            <a href="{{ route('agents.index') }}"
               class="card dash-card shadow-sm"
               style="background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;">
                <div class="card-body text-center">
                    <div class="title-sm">Agents</div>
                    <div class="value">{{ $uniqueAgents }}</div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-6">
            <a href="{{ route('employees.index') }}"
               class="card dash-card shadow-sm"
               style="background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;">
                <div class="card-body text-center">
                    <div class="title-sm">Employees</div>
                    <div class="value">{{ $employees }}</div>
                </div>
            </a>
        </div>

    </div>


    {{-- CHART ROW 1 --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-8">
            <div class="card chart-card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Collection Trend</h5>
                </div>
                <div class="card-body">
                    <div id="trendChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card chart-card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Single 0-9</h5>
                </div>
                <div class="card-body">
                    <div id="singleChart"></div>
                </div>
            </div>
        </div>

    </div>


    {{-- CHART ROW 2 --}}
    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card chart-card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Top Patti</h5>
                </div>
                <div class="card-body">
                    <div id="pattiChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card chart-card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Agent Contribution</h5>
                </div>
                <div class="card-body">
                    <div id="agentChart"></div>
                </div>
            </div>
        </div>

    </div>

        {{-- ROW 3 --}}
    <div class="row g-4 mt-1">

        <div class="col-lg-5">
            <div class="card chart-card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Baji Wise Collection</h5>
                </div>
                <div class="card-body">
                    <div id="bajiChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card chart-card shadow-sm">
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
                                <th>Entry By</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($recentEntries as $entry)
                                <tr>
                                    <td>{{ $entry->created_at->format('d M h:i A') }}</td>
                                    <td>{{ $entry->agent->name ?? '-' }}</td>
                                    <td>{{ $entry->baji->name ?? '-' }}</td>
                                    <td>
                                        <b>{{ $entry->game_number }}</b>
                                    </td>
                                    <td>
                                        ₹{{ number_format($entry->amount,2) }}
                                    </td>
                                    <td>
                                        {{ $entry->employee->name ?? '-' }}
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
/*
|--------------------------------------------------------------------------
| Trend
|--------------------------------------------------------------------------
*/
new ApexCharts(document.querySelector("#trendChart"), {
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
        name:'Collection',
        data:@json($trend->pluck('total')->values())
    }],
    xaxis:{
        categories:@json($trend->pluck('day')->values())
    },
    fill:{
        type:'gradient',
        gradient:{
            opacityFrom:.6,
            opacityTo:.08
        }
    }
}).render();


/*
|--------------------------------------------------------------------------
| Single
|--------------------------------------------------------------------------
*/
new ApexCharts(document.querySelector("#singleChart"), {
    chart:{
        type:'bar',
        height:320,
        toolbar:{show:false}
    },
    dataLabels:{enabled:false},
    series:[{
        name:'Amount',
        data:@json($singleChart)
    }],
    xaxis:{
        categories:['0','1','2','3','4','5','6','7','8','9']
    }
}).render();


/*
|--------------------------------------------------------------------------
| Patti
|--------------------------------------------------------------------------
*/
new ApexCharts(document.querySelector("#pattiChart"), {
    chart:{
        type:'bar',
        height:320,
        toolbar:{show:false}
    },
    plotOptions:{
        bar:{
            horizontal:true,
            borderRadius:6
        }
    },
    dataLabels:{enabled:false},
    series:[{
        name:'Amount',
        data:@json($topPatti->pluck('total')->values())
    }],
    xaxis:{
        categories:@json($topPatti->pluck('game_number')->values())
    }
}).render();


/*
|--------------------------------------------------------------------------
| Agent
|--------------------------------------------------------------------------
*/
const agentLabels = @json($agentChart->pluck('name')->values());
const agentSeries = @json($agentChart->pluck('total')->values());

new ApexCharts(document.querySelector("#agentChart"), {
    chart: {
        type: 'donut',
        height: 320
    },

    labels: agentLabels.length ? agentLabels : ['No Data'],

    series: agentSeries.length ? agentSeries : [1],

    legend: {
        position: 'bottom'
    },

    dataLabels: {
        enabled: true
    },

    stroke: {
        width: 2
    },

    plotOptions: {
        pie: {
            donut: {
                size: '60%'
            }
        }
    },

    noData: {
        text: 'No Data'
    },

    tooltip: {
        y: {
            formatter: function(val){
                return '₹' + val;
            }
        }
    }
}).render();


/*
|--------------------------------------------------------------------------
| Baji
|--------------------------------------------------------------------------
*/
new ApexCharts(document.querySelector("#bajiChart"), {
    chart:{
        type:'bar',
        height:320,
        toolbar:{show:false}
    },
    plotOptions:{
        bar:{
            borderRadius:6,
            columnWidth:'50%'
        }
    },
    dataLabels:{enabled:false},
    series:[{
        name:'Amount',
        data:@json($bajiChart->pluck('total')->values())
    }],
    xaxis:{
        categories:@json($bajiChart->pluck('name')->values())
    }
}).render();
</script>
@endsection