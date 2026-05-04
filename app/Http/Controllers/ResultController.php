<?php

namespace App\Http\Controllers;

use App\Models\Baji;
use App\Models\GameEntry;
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
}