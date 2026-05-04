<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Baji;
use App\Models\User;
use App\Models\GameEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{

    public function index(Request $request)
    {
        if (auth()->user()->hasRole('Employee')) {
            return $this->employeeDashboard();
        }

        return $this->adminDashboard($request);
    }

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE DASHBOARD
    |--------------------------------------------------------------------------
    */
    private function employeeDashboard()
    {
        $userId = auth()->id();

        $todayEntries = GameEntry::where('entry_user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        $todayAmount = GameEntry::where('entry_user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('amount');

        $recentEntries = GameEntry::with(['agent', 'baji'])
            ->where('entry_user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $weekly = GameEntry::selectRaw("
                DATE(created_at) day,
                SUM(amount) total
            ")
            ->where('entry_user_id', $userId)
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('dashboard.employee', compact(
            'todayEntries',
            'todayAmount',
            'recentEntries',
            'weekly'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */
    private function adminDashboard(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | COMMON FILTER
        |--------------------------------------------------------------------------
        */
        $applyFilters = function ($query) use ($request) {

            // today default
            if (
                !$request->filled('date_from') &&
                !$request->filled('date_to')
            ) {
                $query->whereDate('game_entries.created_at', today());
            }

            if ($request->filled('date_from')) {
                $query->whereDate(
                    'game_entries.created_at',
                    '>=',
                    $request->date_from
                );
            }

            if ($request->filled('date_to')) {
                $query->whereDate(
                    'game_entries.created_at',
                    '<=',
                    $request->date_to
                );
            }

            if ($request->filled('baji_id')) {
                $query->where(
                    'game_entries.baji_id',
                    $request->baji_id
                );
            }

            if ($request->filled('agent_id')) {
                $query->where(
                    'game_entries.agent_id',
                    $request->agent_id
                );
            }

            return $query;
        };


        /*
        |--------------------------------------------------------------------------
        | MAIN QUERY
        |--------------------------------------------------------------------------
        */
        $baseQuery = GameEntry::query();
        $applyFilters($baseQuery);


        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */
        $todayCollection = (clone $baseQuery)->sum('amount');

        $todayEntries = (clone $baseQuery)->count();

        $singleCollection = (clone $baseQuery)
            ->whereRaw('CHAR_LENGTH(game_entries.game_number)=1')
            ->sum('amount');

        $pattiCollection = (clone $baseQuery)
            ->whereRaw('CHAR_LENGTH(game_entries.game_number)=3')
            ->sum('amount');

        $uniqueAgents = (clone $baseQuery)
            ->distinct('agent_id')
            ->count('agent_id');


        /*
        |--------------------------------------------------------------------------
        | TREND
        |--------------------------------------------------------------------------
        */
        $trendQuery = GameEntry::query();
        $applyFilters($trendQuery);

        $trend = $trendQuery
            ->selectRaw("
                DATE(game_entries.created_at) day,
                SUM(game_entries.amount) total
            ")
            ->groupBy('day')
            ->orderBy('day')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SINGLE CHART
        |--------------------------------------------------------------------------
        */
        $singleQuery = GameEntry::query();
        $applyFilters($singleQuery);

        $singleData = $singleQuery
            ->whereRaw('CHAR_LENGTH(game_entries.game_number)=1')
            ->selectRaw("
                game_entries.game_number,
                SUM(game_entries.amount) total
            ")
            ->groupBy('game_entries.game_number')
            ->pluck('total', 'game_entries.game_number');

        $singleChart = [];

        for ($i = 0; $i <= 9; $i++) {
            $singleChart[] = $singleData[$i] ?? 0;
        }


        /*
        |--------------------------------------------------------------------------
        | TOP PATTI
        |--------------------------------------------------------------------------
        */
        $pattiQuery = GameEntry::query();
        $applyFilters($pattiQuery);

        $topPatti = $pattiQuery
            ->whereRaw('CHAR_LENGTH(game_entries.game_number)=3')
            ->selectRaw("
                game_entries.game_number,
                SUM(game_entries.amount) total
            ")
            ->groupBy('game_entries.game_number')
            ->orderByDesc('total')
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENT CHART
        |--------------------------------------------------------------------------
        */
        $agentQuery = GameEntry::query();
        $applyFilters($agentQuery);

        /*
        |--------------------------------------------------------------------------
        | AGENT CHART
        |--------------------------------------------------------------------------
        */
        $agentQuery = GameEntry::query();
        $applyFilters($agentQuery);

        $agentChart = $agentQuery
            ->join('agents', 'game_entries.agent_id', '=', 'agents.id')
            ->selectRaw("
                agents.name,
                SUM(game_entries.amount) total
            ")
            ->groupBy('agents.id', 'agents.name')
            ->orderByDesc('total')
            ->get();
        /*
        |--------------------------------------------------------------------------
        | fallback
        |--------------------------------------------------------------------------
        */
        if ($agentChart->isEmpty()) {
            $agentChart = GameEntry::join(
                    'agents',
                    'game_entries.agent_id',
                    '=',
                    'agents.id'
                )
                ->whereDate(
                    'game_entries.created_at',
                    '>=',
                    now()->subDays(30)
                )
                ->selectRaw("
                    agents.name,
                    SUM(game_entries.amount) total
                ")
                ->groupBy('agents.id', 'agents.name')
                ->orderByDesc('total')
                ->take(8)
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | BAJI CHART
        |--------------------------------------------------------------------------
        */
        $bajiQuery = GameEntry::query();
        $applyFilters($bajiQuery);

        $bajiChart = $bajiQuery
            ->join('bajis', 'game_entries.baji_id', '=', 'bajis.id')
            ->selectRaw("
                bajis.name,
                SUM(game_entries.amount) total
            ")
            ->groupBy('bajis.id', 'bajis.name')
            ->orderBy('bajis.id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT ENTRIES
        |--------------------------------------------------------------------------
        */
        $recentQuery = GameEntry::with([
            'agent',
            'baji',
            'employee'
        ]);

        $applyFilters($recentQuery);

        $recentEntries = $recentQuery
            ->latest()
            ->take(15)
            ->get();


        return view('dashboard.admin', [
            'todayCollection'  => $todayCollection,
            'todayEntries'     => $todayEntries,
            'singleCollection' => $singleCollection,
            'pattiCollection'  => $pattiCollection,
            'uniqueAgents'     => $uniqueAgents,
            'trend'            => $trend,
            'singleChart'      => $singleChart,
            'topPatti'         => $topPatti,
            'agentChart'       => $agentChart,
            'bajiChart'        => $bajiChart,
            'recentEntries'    => $recentEntries,
            'agents'           => Agent::orderBy('name')->get(),
            'employees'        => User::role('Employee')->count(),
            'bajis'            => Baji::orderBy('id')->get(),
        ]);
    }
}