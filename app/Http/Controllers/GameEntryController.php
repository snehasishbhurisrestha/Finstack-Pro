<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Baji;
use App\Models\GameEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GameEntryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Game Entry Show', only: ['index']),
            new Middleware('permission:Game Entry Create', only: ['store'])
        ];
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            $agents = Agent::where('status', 1)
                ->orderBy('name')
                ->get();
        } else {
            $agents = Agent::where('employee_id', $user->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        $activeBaji = Baji::active();
        $bajis = Baji::where('status',1)->get();

        $entries = GameEntry::with(['agent', 'baji', 'employee'])
            ->latest()
            ->take(50)
            ->whereDate('created_at',date('Y-m-d'))
            ->get();

        return view('game-entry.index', compact(
            'agents',
            'activeBaji',
            'bajis',
            'entries'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'numbers' => 'required|array|min:1',
            'amounts' => 'required|array|min:1',
        ]);

        // $activeBaji = Baji::active();

        // if (!$activeBaji) {
        //     return back()->with('error', 'No active baji now.');
        // }

        DB::beginTransaction();

        try {

            foreach ($request->numbers as $index => $numberLine) {

                $amount = $request->amounts[$index] ?? null;

                if (!$numberLine || !$amount) {
                    continue;
                }

                $amount = (float)$amount;

                $numberLine = trim($numberLine);

                if (str_contains($numberLine, '.')) {
                    // if dot exists → normal explode
                    $numbers = explode('.', $numberLine);
                } else {
                    if ($request->type == 'single') {
                        // 123456 → 1,2,3,4,5,6
                        $numbers = str_split($numberLine, 1);
                    } elseif ($request->type == 'patti') {
                        // 123456789 → 123,456,789
                        $numbers = str_split($numberLine, 3);
                    } elseif ($request->type == 'cp') {
                        // 1234.5678.9012 → 1234,5678,9012
                        $numbers = explode('.', $numberLine);

                    } else {
                        $numbers = [$numberLine];
                    }
                }

                foreach ($numbers as $number) {

                    $number = trim($number);

                    if ($number == '') {
                        continue;
                    }

                    GameEntry::create([
                        'agent_id' => $request->agent_id,
                        'baji_id' => $request->baji,
                        'game_number' => $number,
                        'amount' => $amount,
                        'entry_user_id' => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            return back()->with(
                'success',
                'Game entry saved successfully'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function update(Request $request, $id)
    {
        $entry = GameEntry::findOrFail($id);

        $entry->update([
            'agent_id' => $request->agent_id,
            'baji_id' => $request->baji_id,
            'game_number' => $request->game_number,
            'amount' => $request->amount,
        ]);

        return back()->with('success', 'Entry updated successfully');
    }

    public function destroy($id)
    {
        GameEntry::findOrFail($id)->delete();

        return back()->with('success', 'Entry deleted successfully');
    }

    public function bulkList(Request $request)
    {
        $entries = GameEntry::with(['agent', 'baji'])
            ->whereBetween('created_at', [
                $request->from,
                $request->to
            ])
            ->latest()
            ->get();

        return response()->json($entries);
    }

    public function bulkUpdate(Request $request)
    {
        if (!$request->entry_ids) {
            return back()->with('error', 'No entries selected');
        }

        $updateData = [];

        // ONLY UPDATE AGENT IF SELECTED
        if ($request->filled('agent_id')) {
            $updateData['agent_id'] = $request->agent_id;
        }

        // ONLY UPDATE BAJI IF SELECTED
        if ($request->filled('baji_id')) {
            $updateData['baji_id'] = $request->baji_id;
        }

        // NOTHING SELECTED
        if (empty($updateData)) {
            return back()->with(
                'error',
                'Please select Agent or Baji to update'
            );
        }

        GameEntry::whereIn('id', $request->entry_ids)
            ->update($updateData);

        return back()->with(
            'success',
            count($request->entry_ids) . ' entries updated successfully'
        );
    }
}