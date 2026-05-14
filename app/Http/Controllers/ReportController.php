<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Baji;
use App\Models\GameEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Entry Report', only: ['index'])
        ];
    }

    /*public function index(Request $request)
    {
        $bigAmount = $request->big_amount ?? 500;

        $applyFilters = function ($query) use ($request) {

            if ($request->filled('agent_id')) {
                $query->where('agent_id', $request->agent_id);
            }

            if ($request->filled('baji_id')) {
                $query->where('baji_id', $request->baji_id);
            }

            if ($request->filled('game_number')) {
                $query->where(
                    'game_number',
                    'like',
                    '%' . $request->game_number . '%'
                );
            }

            if ($request->filled('date_from')) {
                $query->whereDate(
                    'created_at',
                    '>=',
                    $request->date_from
                );
            }

            if ($request->filled('date_to')) {
                $query->whereDate(
                    'created_at',
                    '<=',
                    $request->date_to
                );
            }

            if ($request->filled('min_amount')) {
                $query->where(
                    'amount',
                    '>=',
                    $request->min_amount
                );
            }

            if ($request->filled('max_amount')) {
                $query->where(
                    'amount',
                    '<=',
                    $request->max_amount
                );
            }

            return $query;
        };

        $entryQuery = GameEntry::with([
            'agent',
            'baji',
            'employee'
        ]);

        $applyFilters($entryQuery);

        // sort
        if ($request->sort == 'amount_asc') {
            $entryQuery->orderBy('amount');
        } elseif ($request->sort == 'amount_desc') {
            $entryQuery->orderByDesc('amount');
        } elseif ($request->sort == 'number_asc') {
            $entryQuery->orderBy('game_number');
        } elseif ($request->sort == 'number_desc') {
            $entryQuery->orderByDesc('game_number');
        } else {
            $entryQuery->latest();
        }

        $summaryQuery = GameEntry::query();
        $applyFilters($summaryQuery);

        $summary = $summaryQuery
            ->selectRaw("
                COUNT(*) as total_entries,
                COALESCE(SUM(amount),0) as total_amount,
                COUNT(DISTINCT game_number) as unique_numbers,
                COUNT(DISTINCT agent_id) as unique_agents
            ")
            ->first();

        $entries = $entryQuery
            ->paginate(50)
            ->withQueryString();


        $numberQuery = GameEntry::query();
        $applyFilters($numberQuery);

        $numberSummary = $numberQuery
            ->select(
                'game_number',
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(DISTINCT agent_id) as total_agents')
            )
            ->groupBy('game_number')
            ->orderByDesc('total_amount')
            ->get();

        $agentQuery = GameEntry::query();
        $applyFilters($agentQuery);

        $agentSummary = $agentQuery
            ->join('agents', 'game_entries.agent_id', '=', 'agents.id')
            ->select(
                'agents.id',
                'agents.name',
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(game_entries.amount) as total_amount'),
                DB::raw('COUNT(DISTINCT game_entries.game_number) as total_numbers')
            )
            ->groupBy('agents.id', 'agents.name')
            ->orderByDesc('total_amount')
            ->get();

        $bigBetQuery = GameEntry::with([
            'agent',
            'baji',
            'employee'
        ]);

        $applyFilters($bigBetQuery);

        $bigBets = $bigBetQuery
            ->where('amount', '>=', $bigAmount)
            ->latest()
            ->get();


        return view('reports.index', [
            'entries' => $entries,
            'summary' => $summary,
            'numberSummary' => $numberSummary,
            'agentSummary' => $agentSummary,
            'bigBets' => $bigBets,
            'agents' => Agent::orderBy('name')->get(),
            'bajis' => Baji::orderBy('id')->get(),
            'bigAmount' => $bigAmount,
        ]);
    }*/

    public function index(Request $request)
    {
        $bigAmount = $request->big_amount ?? 500;

        /*
        |--------------------------------------------------------------------------
        | BASE FILTER FUNCTION
        |--------------------------------------------------------------------------
        */
        $applyFilters = function ($query) use ($request) {

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE CAN SEE ONLY OWN ENTRY
            |--------------------------------------------------------------------------
            */
            if (auth()->user()->hasRole('Employee')) {
                $query->where('game_entries.entry_user_id', auth()->id());
            }

            /*
            |--------------------------------------------------------------------------
            | DEFAULT DATE = TODAY
            |--------------------------------------------------------------------------
            */
            if (!$request->filled('date_from') && !$request->filled('date_to')) {
                $query->whereDate('game_entries.created_at', today());
            }

            /*
            |--------------------------------------------------------------------------
            | CUSTOM FILTERS
            |--------------------------------------------------------------------------
            */
            if ($request->filled('agent_id')) {
                $query->where('game_entries.agent_id', $request->agent_id);
            }

            if ($request->filled('baji_id')) {
                $query->where('game_entries.baji_id', $request->baji_id);
            }

            if ($request->filled('game_number')) {
                $query->where(
                    'game_entries.game_number',
                    'like',
                    '%' . $request->game_number . '%'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | GAME TYPE FILTER
            |--------------------------------------------------------------------------
            */
            if ($request->filled('game_type')) {

                // SINGLE = 1 digit
                if ($request->game_type == 'single') {

                    $query->whereRaw('CHAR_LENGTH(game_entries.game_number)=1');

                }

                // PATTI = 3 digit
                if ($request->game_type == 'patti') {

                    $query->whereRaw('CHAR_LENGTH(game_entries.game_number)=3');

                }
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

            if ($request->filled('min_amount')) {
                $query->where(
                    'game_entries.amount',
                    '>=',
                    $request->min_amount
                );
            }

            if ($request->filled('max_amount')) {
                $query->where(
                    'game_entries.amount',
                    '<=',
                    $request->max_amount
                );
            }

            return $query;
        };


        /*
        |--------------------------------------------------------------------------
        | ALL ENTRY QUERY
        |--------------------------------------------------------------------------
        */
        $entryQuery = GameEntry::with([
            'agent',
            'baji',
            'employee'
        ]);

        $applyFilters($entryQuery);

        // sorting
        if ($request->sort == 'amount_asc') {
            $entryQuery->orderBy('game_entries.amount');
        } elseif ($request->sort == 'amount_desc') {
            $entryQuery->orderByDesc('game_entries.amount');
        } elseif ($request->sort == 'number_asc') {
            $entryQuery->orderBy('game_entries.game_number');
        } elseif ($request->sort == 'number_desc') {
            $entryQuery->orderByDesc('game_entries.game_number');
        } else {
            $entryQuery->latest('game_entries.created_at');
        }


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */
        $summaryQuery = GameEntry::query();
        $applyFilters($summaryQuery);

        $summary = $summaryQuery
            ->selectRaw("
                COUNT(*) as total_entries,
                COALESCE(SUM(amount),0) as total_amount,
                COUNT(DISTINCT game_number) as unique_numbers,
                COUNT(DISTINCT agent_id) as unique_agents
            ")
            ->first();


        /*
        |--------------------------------------------------------------------------
        | ALL ENTRIES
        |--------------------------------------------------------------------------
        */
        $entries = $entryQuery->get();
            // ->paginate(50)
            // ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | NUMBER SUMMARY
        |--------------------------------------------------------------------------
        */
        $numberQuery = GameEntry::query();
        $applyFilters($numberQuery);

        $numberSummary = $numberQuery
            ->select(
                'game_number',
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(DISTINCT agent_id) as total_agents')
            )
            ->groupBy('game_number')
            ->orderByDesc('total_amount')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENT SUMMARY
        |--------------------------------------------------------------------------
        */
        $agentQuery = GameEntry::query();
        $applyFilters($agentQuery);

        $agentSummary = $agentQuery
            ->join('agents', 'game_entries.agent_id', '=', 'agents.id')
            ->select(
                'agents.id',
                'agents.name',
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(game_entries.amount) as total_amount'),
                DB::raw('COUNT(DISTINCT game_entries.game_number) as total_numbers')
            )
            ->groupBy('agents.id', 'agents.name')
            ->orderByDesc('total_amount')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BIG BETS
        |--------------------------------------------------------------------------
        */
        $bigBetQuery = GameEntry::with([
            'agent',
            'baji',
            'employee'
        ]);

        $applyFilters($bigBetQuery);

        $bigBets = $bigBetQuery
            ->where('game_entries.amount', '>=', $bigAmount)
            ->latest('game_entries.created_at')
            ->get();


        return view('reports.index', [
            'entries'       => $entries,
            'summary'       => $summary,
            'numberSummary' => $numberSummary,
            'agentSummary'  => $agentSummary,
            'bigBets'       => $bigBets,
            // 'agents'        => Agent::orderBy('name')->get(),
            'agents' => auth()->user()->hasRole('Employee')
                ? Agent::where('employee_id', auth()->id())
                    ->orderBy('name')
                    ->get()
                : Agent::orderBy('name')->get(),
            'bajis'         => Baji::orderBy('id')->get(),
            'bigAmount'     => $bigAmount,
        ]);
    }
}