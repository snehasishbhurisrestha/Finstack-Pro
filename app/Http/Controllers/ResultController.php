<?php

namespace App\Http\Controllers;

use App\Models\Baji;
use App\Models\GameEntry;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ResultController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Result Check', only: ['index'])
        ];
    }

    public function index_old(Request $request)
    {
        $bajis = Baji::orderBy('id')->get();

        $result = null;

        if ($request->isMethod('post')) {

            $request->validate([
                'baji_id' => 'required|exists:bajis,id',
                'patti'   => 'required|digits:3',
            ]);

            $patti = trim($request->patti);

            /*
            |--------------------------------------------------------------------------
            | Calculate Single
            |--------------------------------------------------------------------------
            | 578 => 5+7+8 = 20 => 0
            | 367 => 3+6+7 = 16 => 6
            | 133 => 1+3+3 = 7 => 7
            */
            $sum = array_sum(str_split($patti));
            $single = $sum % 10;


            /*
            |--------------------------------------------------------------------------
            | Winning Entries (same baji only)
            |--------------------------------------------------------------------------
            */
            $winningEntries = GameEntry::with(['agent','baji','employee'])
                ->where('baji_id', $request->baji_id)
                ->whereDate('created_at',date('Y-m-d'))
                ->where(function ($q) use ($patti, $single) {
                    $q->where('game_number', $patti)
                      ->orWhere('game_number', (string)$single);
                })
                ->latest()
                ->get();


            /*
            |--------------------------------------------------------------------------
            | Patti Winners
            |--------------------------------------------------------------------------
            */
            $pattiWinners = GameEntry::where('baji_id', $request->baji_id)
                ->where('game_number', $patti)
                ->whereDate('created_at',date('Y-m-d'))
                ->get();

            $pattiCount = $pattiWinners->count();
            $pattiAmount = $pattiWinners->sum('amount');


            /*
            |--------------------------------------------------------------------------
            | Single Winners
            |--------------------------------------------------------------------------
            */
            $singleWinners = GameEntry::where('baji_id', $request->baji_id)
                ->where('game_number', (string)$single)
                ->whereDate('created_at',date('Y-m-d'))
                ->get();

            $singleCount = $singleWinners->count();
            $singleAmount = $singleWinners->sum('amount');


            /*
            |--------------------------------------------------------------------------
            | Agent Wise Summary
            |--------------------------------------------------------------------------
            */
            $agentSummary = GameEntry::join('agents', 'game_entries.agent_id', '=', 'agents.id')
                ->where('game_entries.baji_id', $request->baji_id)
                ->whereDate('game_entries.created_at',date('Y-m-d'))
                ->where(function ($q) use ($patti, $single) {
                    $q->where('game_entries.game_number', $patti)
                      ->orWhere('game_entries.game_number', (string)$single);
                })
                ->select(
                    'agents.name',
                    DB::raw("
                        SUM(
                            CASE
                                WHEN game_entries.game_number = '$patti'
                                THEN amount
                                ELSE 0
                            END
                        ) as patti_amount
                    "),
                    DB::raw("
                        SUM(
                            CASE
                                WHEN game_entries.game_number = '$single'
                                THEN amount
                                ELSE 0
                            END
                        ) as single_amount
                    "),
                    DB::raw("SUM(amount) as total_amount"),
                    DB::raw("COUNT(*) as total_entry")
                )
                ->groupBy('agents.id','agents.name')
                ->orderByDesc('total_amount')
                ->get();


            $result = [
                'patti' => $patti,
                'single' => $single,
                'pattiCount' => $pattiCount,
                'pattiAmount' => $pattiAmount,
                'singleCount' => $singleCount,
                'singleAmount' => $singleAmount,
                'grandTotal' => $pattiAmount + $singleAmount,
                'agentSummary' => $agentSummary,
                'winningEntries' => $winningEntries,
            ];
        }

        return view('results.index', compact(
            'bajis',
            'result'
        ));
    }

    public function index(Request $request)
    {
        $bajis = Baji::orderBy('id')->get();

        $result = null;

        if ($request->isMethod('post')) {

            $request->validate([
                'baji_id' => 'required|exists:bajis,id',
                'patti'   => 'required|digits:3',
            ]);

            $patti = trim($request->patti);

            /*
            |--------------------------------------------------------------------------
            | Calculate Single
            |--------------------------------------------------------------------------
            */
            $sum = array_sum(str_split($patti));
            $single = $sum % 10;

            /*
            |--------------------------------------------------------------------------
            | CP Match Checker
            |--------------------------------------------------------------------------
            */
            $isCpWinner = function ($cp, $patti) {

                // CP must be 4-7 digits only
                if (!preg_match('/^\d{4,7}$/', $cp)) {
                    return false;
                }

                // Patti must have 3 unique digits
                if (count(array_unique(str_split($patti))) != 3) {
                    return false;
                }

                // Ordered subsequence match
                $pos = 0;

                foreach (str_split($patti) as $digit) {
                    $found = strpos($cp, $digit, $pos);

                    if ($found === false) {
                        return false;
                    }

                    $pos = $found + 1;
                }

                return true;
            };

            /*
            |--------------------------------------------------------------------------
            | All entries of selected baji today
            |--------------------------------------------------------------------------
            */
            $todayEntries = GameEntry::with(['agent', 'baji', 'employee'])
                ->where('baji_id', $request->baji_id)
                ->whereDate('created_at', date('Y-m-d'))
                ->latest()
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Winners
            |--------------------------------------------------------------------------
            */
            $winningEntries = $todayEntries->filter(function ($entry) use ($patti, $single, $isCpWinner) {
                return $entry->game_number == $patti
                    || $entry->game_number == (string) $single
                    || $isCpWinner($entry->game_number, $patti);
            });

            /*
            |--------------------------------------------------------------------------
            | Patti Winners
            |--------------------------------------------------------------------------
            */
            $pattiWinners = $todayEntries->where('game_number', $patti);
            $pattiCount   = $pattiWinners->count();
            $pattiAmount  = $pattiWinners->sum('amount');

            /*
            |--------------------------------------------------------------------------
            | Single Winners
            |--------------------------------------------------------------------------
            */
            $singleWinners = $todayEntries->where('game_number', (string) $single);
            $singleCount   = $singleWinners->count();
            $singleAmount  = $singleWinners->sum('amount');

            /*
            |--------------------------------------------------------------------------
            | CP Winners
            |--------------------------------------------------------------------------
            */
            $cpWinners = $todayEntries->filter(function ($entry) use ($patti, $isCpWinner) {
                return $isCpWinner($entry->game_number, $patti);
            });

            $cpCount  = $cpWinners->count();
            $cpAmount = $cpWinners->sum('amount');

            /*
            |--------------------------------------------------------------------------
            | Agent Wise Summary
            |--------------------------------------------------------------------------
            */
            $agentSummary = $winningEntries
                ->groupBy('agent_id')
                ->map(function ($entries) use ($patti, $single, $isCpWinner) {

                    $first = $entries->first();

                    $pattiAmount = $entries
                        ->where('game_number', $patti)
                        ->sum('amount');

                    $singleAmount = $entries
                        ->where('game_number', (string) $single)
                        ->sum('amount');

                    $cpAmount = $entries
                        ->filter(fn($e) => $isCpWinner($e->game_number, $patti))
                        ->sum('amount');

                    return (object)[
                        'name'         => $first->agent->name,
                        'patti_amount' => $pattiAmount,
                        'single_amount'=> $singleAmount,
                        'cp_amount'    => $cpAmount,
                        'total_amount' => $pattiAmount + $singleAmount + $cpAmount,
                        'total_entry'  => $entries->count(),
                    ];
                })
                ->sortByDesc('total_amount')
                ->values();

            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */
            $result = [
                'patti'         => $patti,
                'single'        => $single,
                'pattiCount'    => $pattiCount,
                'pattiAmount'   => $pattiAmount,
                'singleCount'   => $singleCount,
                'singleAmount'  => $singleAmount,
                'cpCount'       => $cpCount,
                'cpAmount'      => $cpAmount,
                'grandTotal'    => $pattiAmount + $singleAmount + $cpAmount,
                'agentSummary'  => $agentSummary,
                'winningEntries'=> $winningEntries,
            ];


            if ($request->action_type == 'submit') {

                // only today's result allowed
                $resultDate = now()->toDateString();

                // already submitted?
                $alreadySubmitted = Result::where('baji_id', $request->baji_id)
                    ->whereDate('result_date', $resultDate)
                    ->exists();

                if ($alreadySubmitted) {
                    return back()->with('error', 'Today result already submitted for this Baji.');
                }

                Result::create([
                    'baji_id'            => $request->baji_id,
                    'result_date'        => $resultDate,
                    'patti'              => $patti,
                    'single'             => $single,
                    'patti_win_amount'   => $pattiAmount,
                    'single_win_amount'  => $singleAmount,
                    'cp_win_amount'      => $cpAmount,
                    'patti_win_count'    => $pattiCount,
                    'single_win_count'   => $singleCount,
                    'cp_win_count'       => $cpCount,
                    'total_entries'      => $todayEntries->count(),
                    'total_collection'   => $todayEntries->sum('amount'),
                    'total_liability'    => $pattiAmount + $singleAmount + $cpAmount,
                    'profit_loss'        => $todayEntries->sum('amount') - ($pattiAmount + $singleAmount + $cpAmount),
                    'declared_by'        => auth()->id(),
                ]);

                GameEntry::whereIn('id', $winningEntries->pluck('id'))
                    ->update(['is_win' => 1]);
                return back()->with('success', 'Result submitted Successfully.');
            }
        }

        return view('results.index', compact('bajis', 'result'));
    }

    public function history(Request $request)
    {
        $bajis = Baji::orderBy('name')->get();

        $query = Result::with(['baji', 'user']);

        // Date from
        if ($request->filled('date_from')) {
            $query->whereDate('result_date', '>=', $request->date_from);
        }

        // Date to
        if ($request->filled('date_to')) {
            $query->whereDate('result_date', '<=', $request->date_to);
        }

        // Baji
        if ($request->filled('baji_id')) {
            $query->where('baji_id', $request->baji_id);
        }

        // Patti
        if ($request->filled('patti')) {
            $query->where('patti', 'like', "%{$request->patti}%");
        }

        // Single
        if ($request->filled('single')) {
            $query->where('single', $request->single);
        }

        // Min liability
        if ($request->filled('min_liability')) {
            $query->where('total_liability', '>=', $request->min_liability);
        }

        // Max liability
        if ($request->filled('max_liability')) {
            $query->where('total_liability', '<=', $request->max_liability);
        }

        // Sort
        if ($request->sort == 'profit_high') {
            $query->orderByDesc('profit_loss');
        } elseif ($request->sort == 'profit_low') {
            $query->orderBy('profit_loss');
        } elseif ($request->sort == 'liability_high') {
            $query->orderByDesc('total_liability');
        } else {
            $query->latest('result_date');
        }

        $results = $query->paginate(30)->withQueryString();

        return view('results.history', compact('results', 'bajis'));
    }

    public function show(Result $result)
    {
        $entries = GameEntry::with(['agent','baji','employee'])
            ->where('baji_id', $result->baji_id)
            ->whereDate('created_at', $result->result_date)
            ->where('is_win', 1)
            ->latest()
            ->get();

        $agentSummary = $entries
            ->groupBy('agent_id')
            ->map(function ($rows) {
                $first = $rows->first();

                return (object)[
                    'name' => $first->agent->name,
                    'total_amount' => $rows->sum('amount'),
                    'total_entry' => $rows->count(),
                ];
            })
            ->sortByDesc('total_amount')
            ->values();

        return view('results.show', compact(
            'result',
            'entries',
            'agentSummary'
        ));
    }
}