@extends('layouts.app')
@section('title','Result Check')
@section('content')
<div class="container-fluid py-3">

    {{-- FORM --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">Result Check</h5>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('results.index') }}">
                @csrf

                <div class="row g-3 align-items-end mt-2">

                    <div class="col-lg-4">
                        <label class="fw-bold mb-2">Select Baji</label>
                        <select name="baji_id" class="form-select" required>
                            <option value="">Choose Baji</option>
                            @foreach($bajis as $baji)
                                <option value="{{ $baji->id }}" {{ old('baji_id', request('baji_id')) == $baji->id ? 'selected' : '' }}>
                                    {{ $baji->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="fw-bold mb-2">Winning Patti</label>
                        <input type="text"
                               name="patti"
                               maxlength="3"
                               minlength="3"
                               pattern="[0-9]{3}"
                               value="{{ old('patti', request('patti')) }}"
                               class="form-control"
                               placeholder="578"
                               required>
                    </div>

                    <input type="hidden" name="action_type" id="action_type" value="check">

                    <div class="col-lg-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="submit"
                                        onclick="$('#action_type').val('check')"
                                        class="btn btn-success w-100">
                                    CHECK RESULT
                                </button>
                            </div>

                            <div class="col-6">
                                <button type="button"
                                        id="submitResultBtn"
                                        class="btn btn-warning w-100">
                                    SUBMIT RESULT
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>


    @if($result)

        {{-- RESULT HEADER --}}
        <div class="row g-3 mb-4">

            <div class="col-md-2 col-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <small>Winning Patti</small>
                        <h2 class="mb-0 text-primary">
                            {{ $result['patti'] }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <small>Winning Single</small>
                        <h2 class="mb-0 text-success">
                            {{ $result['single'] }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <small>CP Win Amount</small>
                        <h4 class="mb-0 text-warning">
                            ₹{{ number_format($result['cpAmount'],2) }}
                        </h4>
                        <small>
                            {{ $result['cpCount'] }} entries
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <small>Patti Win Amount</small>
                        <h4 class="mb-0">
                            ₹{{ number_format($result['pattiAmount'],2) }}
                        </h4>
                        <small>
                            {{ $result['pattiCount'] }} entries
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center bg-danger text-white">
                    <div class="card-body">
                        <small>Total Liability</small>
                        <h3 class="mb-0 text-white">
                            ₹{{ number_format($result['grandTotal'],2) }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>


        {{-- AGENT SUMMARY --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Agent Wise Winning</h5>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Patti Amount</th>
                            <th>Single Amount</th>
                            <th>CP Amount</th>
                            <th>Total Entry</th>
                            <th>Total Win</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($result['agentSummary'] as $row)
                            <tr>
                                <td><b>{{ $row->name }}</b></td>
                                <td>₹{{ number_format($row->patti_amount,2) }}</td>
                                <td>₹{{ number_format($row->single_amount,2) }}</td>
                                <td>₹{{ number_format($row->cp_amount,2) }}</td>
                                <td>{{ $row->total_entry }}</td>
                                <td class="fw-bold text-danger">
                                    ₹{{ number_format($row->total_amount,2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No winner found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        {{-- DETAILS --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Winning Entry Details</h5>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
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
                        @forelse($result['winningEntries'] as $entry)
                            <tr>
                                <td>{{ $entry->created_at->format('d M h:i A') }}</td>
                                <td>{{ $entry->agent->name ?? '-' }}</td>
                                <td>{{ $entry->baji->name ?? '-' }}</td>

                                <td>
                                    @if(strlen($entry->game_number) == 3)
                                        <span class="badge bg-primary">
                                            {{ $entry->game_number }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ $entry->game_number }}
                                        </span>
                                    @endif
                                </td>

                                <td>₹{{ $entry->amount }}</td>
                                <td>{{ $entry->employee->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No winner found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>
@endsection

@section('script')
<script>
    $('#submitResultBtn').on('click', function () {
        Swal.fire({
            title: 'Confirm Result Submit?',
            text: 'Are you checked the result?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Submit',
            cancelButtonText: 'No',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#dc3545',
        }).then((res) => {
            if (res.isConfirmed) {
                $('#action_type').val('submit');
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection