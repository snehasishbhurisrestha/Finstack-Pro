<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Baji;
use App\Models\GameEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnalyticsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Single Report', only: ['singleReport']),
            new Middleware('permission:Patti Report', only: ['pattiReport'])
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SINGLE REPORT
    |--------------------------------------------------------------------------
    */
    public function singleReport(Request $request)
    {
        $query = GameEntry::query()
            ->whereRaw('CHAR_LENGTH(game_number)=1');

        /*
        |--------------------------------------------------------------------------
        | TODAY DEFAULT
        |--------------------------------------------------------------------------
        */
        if (
            !$request->filled('date_from') &&
            !$request->filled('date_to')
        ) {
            $query->whereDate(
                'created_at',
                Carbon::today()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */
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

        if ($request->filled('baji_id')) {
            $query->where(
                'baji_id',
                $request->baji_id
            );
        }

        if ($request->filled('agent_id')) {
            $query->where(
                'agent_id',
                $request->agent_id
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

        /*
        |--------------------------------------------------------------------------
        | FETCH GROUPED
        |--------------------------------------------------------------------------
        */
        $rows = $query
            ->select(
                'game_number',
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(DISTINCT agent_id) as total_agents')
            )
            ->groupBy('game_number')
            ->get()
            ->keyBy('game_number');

        /*
        |--------------------------------------------------------------------------
        | MAKE 0-9 FIXED
        |--------------------------------------------------------------------------
        */
        $report = collect();

        for ($i = 0; $i <= 9; $i++) {

            $row = $rows->get((string)$i);

            $report->push((object)[
                'game_number' => $i,
                'total_entry' => $row->total_entry ?? 0,
                'total_amount' => $row->total_amount ?? 0,
                'total_agents' => $row->total_agents ?? 0,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */
        if ($request->sort == 'amount_asc') {
            $report = $report->sortBy('total_amount');
        }
        elseif ($request->sort == 'amount_desc') {
            $report = $report->sortByDesc('total_amount');
        }
        elseif ($request->sort == 'entry_asc') {
            $report = $report->sortBy('total_entry');
        }
        elseif ($request->sort == 'entry_desc') {
            $report = $report->sortByDesc('total_entry');
        }

        $report = $report->values();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */
        $summary = [
            'total_amount' => $report->sum('total_amount'),
            'total_entry' => $report->sum('total_entry'),
            'highest' => $report->sortByDesc('total_amount')->first(),
            'lowest' => $report->sortBy('total_amount')->first(),
        ];

        return view('reports.single', [
            'report' => $report,
            'summary' => $summary,
            'agents' => Agent::orderBy('name')->get(),
            'bajis' => Baji::orderBy('id')->get(),
        ]);
    }

    public function singleAgentDetails(Request $request)
    {
        $query = GameEntry::query()
            ->whereRaw('CHAR_LENGTH(game_number)=1')
            ->where('game_number', $request->number);

        /*
        |--------------------------------------------------------------------------
        | SAME FILTERS
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_from')) {
            $query->whereDate('game_entries.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('game_entries.created_at', '<=', $request->date_to);
        }

        if ($request->filled('baji_id')) {
            $query->where('baji_id', $request->baji_id);
        }

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $data = $query
            ->join('agents', 'agents.id', '=', 'game_entries.agent_id')
            ->select(
                'agents.name',
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('agents.id', 'agents.name')
            ->orderByDesc('total_entry')
            ->get();

        return response()->json($data);
    }


    /*
    |--------------------------------------------------------------------------
    | PATTI REPORT
    |--------------------------------------------------------------------------
    */
    public function pattiReport(Request $request)
    {
        $query = GameEntry::query()
            ->whereRaw('CHAR_LENGTH(game_number)=3');

        /*
        |--------------------------------------------------------------------------
        | TODAY DEFAULT
        |--------------------------------------------------------------------------
        */
        if (
            !$request->filled('date_from') &&
            !$request->filled('date_to')
        ) {
            $query->whereDate(
                'created_at',
                Carbon::today()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */
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

        if ($request->filled('baji_id')) {
            $query->where(
                'baji_id',
                $request->baji_id
            );
        }

        if ($request->filled('agent_id')) {
            $query->where(
                'agent_id',
                $request->agent_id
            );
        }

        if ($request->filled('game_number')) {
            $query->where(
                'game_number',
                'like',
                '%' . $request->game_number . '%'
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


        /*
        |--------------------------------------------------------------------------
        | PATTI STARTING DIGIT SUMMARY (0-9)
        |--------------------------------------------------------------------------
        */
        $startDigitSummary = (clone $query)
            ->select(
                DB::raw('LEFT(game_number,1) as start_digit'),
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('start_digit')
            ->orderBy('start_digit')
            ->get()
            ->keyBy('start_digit');

        /*
        |--------------------------------------------------------------------------
        | MAKE 0-9 ALWAYS AVAILABLE
        |--------------------------------------------------------------------------
        */
        $digitReport = collect();

        for ($i = 0; $i <= 9; $i++) {
            $digitReport->push([
                'digit' => (string)$i,
                'total_entry' => $startDigitSummary[$i]->total_entry ?? 0,
                'total_amount' => $startDigitSummary[$i]->total_amount ?? 0,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP
        |--------------------------------------------------------------------------
        */
        $query = $query->select(
            'game_number',
            DB::raw('COUNT(*) as total_entry'),
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(DISTINCT agent_id) as total_agents')
        )
        ->groupBy('game_number');

        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */
        if ($request->sort == 'number_asc') {
            $query->orderBy('game_number');
        }
        elseif ($request->sort == 'number_desc') {
            $query->orderByDesc('game_number');
        }
        elseif ($request->sort == 'amount_asc') {
            $query->orderBy('total_amount');
        }
        elseif ($request->sort == 'amount_desc') {
            $query->orderByDesc('total_amount');
        }
        elseif ($request->sort == 'entry_asc') {
            $query->orderBy('total_entry');
        }
        else {
            $query->orderByDesc('total_entry');
        }

        $report = $query->get();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */
        $summary = [
            'total_amount' => $report->sum('total_amount'),
            'total_entry' => $report->sum('total_entry'),
            'unique_count' => $report->count(),
            'highest' => $report->sortByDesc('total_amount')->first(),
        ];

        return view('reports.patti', [
            'report' => $report,
            'digitReport' => $digitReport,
            'summary' => $summary,
            'agents' => Agent::orderBy('name')->get(),
            'bajis' => Baji::orderBy('id')->get(),
        ]);
    }
}