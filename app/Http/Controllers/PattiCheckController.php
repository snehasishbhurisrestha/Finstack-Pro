<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Baji;
use App\Models\GameEntry;
use App\Models\PattiCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PattiCheckController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Patti Check', only: ['index']),
            new Middleware('permission:Game Entry Create', only: ['store'])
        ];
    }

    public function index(Request $request)
    {
        $bajis = Baji::orderBy('id')->get();

        $pattis = PattiCheck::select('single', 'patti')
            ->orderBy('single')
            ->get()
            ->groupBy('single');

        $maxRows = $pattis->max(fn($group) => $group->count());

        $amountData = [];

        // Only run when filter submitted
        if ($request->filled('baji_id') || $request->filled('date')) {

            $query = GameEntry::query()
                ->whereRaw('CHAR_LENGTH(game_number) = 3');

            if ($request->filled('baji_id')) {
                $query->where('baji_id', $request->baji_id);
            }

            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            $amountData = $query
                ->select(
                    'game_number',
                    DB::raw('SUM(amount) as total_amount')
                )
                ->groupBy('game_number')
                ->pluck('total_amount', 'game_number')
                ->toArray();
        }

        $summary = [
            'total_amount' => 0,
            'total_entry'  => 0,
            'unique_count' => 0,
            'highest'      => null,
        ];

        if ($request->filled('baji_id') || $request->filled('date')) {

            $summaryQuery = GameEntry::query()
                ->whereRaw('CHAR_LENGTH(game_number) = 3');

            if ($request->filled('baji_id')) {
                $summaryQuery->where('baji_id', $request->baji_id);
            }

            if ($request->filled('date')) {
                $summaryQuery->whereDate('created_at', $request->date);
            }

            $summary['total_amount'] = (clone $summaryQuery)->sum('amount');

            $summary['total_entry'] = (clone $summaryQuery)->count();

            $summary['unique_count'] = (clone $summaryQuery)
                ->distinct('game_number')
                ->count('game_number');

            $summary['highest'] = (clone $summaryQuery)
                ->select(
                    'game_number',
                    DB::raw('SUM(amount) as total_amount')
                )
                ->groupBy('game_number')
                ->orderByDesc('total_amount')
                ->first();
        }

        return view('patti-check.index', compact(
            'bajis',
            'pattis',
            'maxRows',
            'amountData',
            'summary'
        ));
    }

    public function details(Request $request)
    {
        $query = GameEntry::query()
            ->join('agents', 'agents.id', '=', 'game_entries.agent_id')
            ->where('game_number', $request->patti);

        if ($request->filled('baji_id')) {
            $query->where('baji_id', $request->baji_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('game_entries.created_at', $request->date);
        }

        $data = $query
            ->select(
                'agents.name',
                DB::raw('COUNT(*) as total_entry'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('agents.id', 'agents.name')
            ->orderByDesc('total_amount')
            ->get();

        return response()->json($data);
    }

    public function store_index()
    {
        return view('patti-check.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numbers' => 'required',
        ]);

        $numberLine = trim($request->numbers);

        if (str_contains($numberLine, '.')) {
            // if dot exists → normal explode
            $numbers = explode('.', $numberLine);
        }else {
            // if no dot → split every 2 characters
            $numbers = str_split($numberLine, 3);
        }

        foreach ($numbers as $number) {

            $number = trim($number);

            if ($number == '') {
                continue;
            }

            $sum = array_sum(str_split($number));
            $single = $sum % 10;

            PattiCheck::create([
                'single' => $single,
                'patti' => $number,
            ]);
        }

        return back()->with(
            'success',
            'Patti entry saved successfully'
        );
    }
}