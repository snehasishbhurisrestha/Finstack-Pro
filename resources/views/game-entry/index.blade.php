@extends('layouts.app')

@section('style')
<style>
    .entry-card{
        border:none;
        border-radius:16px;
        overflow:hidden;
    }

    .soft-box{
        background:#fff;
        border:1px solid #e8e8e8;
        border-radius:14px;
        padding:15px;
    }

    .select-card{
        cursor:pointer;
        border:2px solid #ddd;
        border-radius:12px;
        padding:12px;
        text-align:center;
        font-weight:600;
        transition:.2s;
        background:#fff;
    }

    .select-card:has(input:checked){
        border-color:#0d6efd;
        background:#eef5ff;
        color:#0d6efd;
    }

    .select-card input{
        display:none;
    }

    .entry-row{
        background:#f8fafc;
        border-radius:12px;
        padding:10px;
        margin-bottom:10px;
    }

    .sticky-save{
        position:sticky;
        bottom:0;
        background:#fff;
        padding:12px;
        border-top:1px solid #ddd;
        z-index:50;
    }

    .add-btn{
        width:45px;
        height:45px;
        border-radius:50%;
        font-size:22px;
        padding:0;
    }

    @media(max-width:768px){
        .mobile-scroll{
            max-height:180px;
            overflow:auto;
        }

        .form-control,
        .form-select{
            height:50px;
        }
    }
</style>
@endsection

@section('content')

<div class="container-fluid">


    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">Game Entry</h5>
        </div>

        <div class="card-body">

            {{-- BAJI --}}
            {{-- <div class="mb-4 text-center">
                @if($activeBaji)
                    <span class="badge bg-success fs-5 px-4 py-3">
                        Active Baji :
                        {{ $activeBaji->name }}
                        ({{ date('h:i A', strtotime($activeBaji->start_time)) }}
                        -
                        {{ date('h:i A', strtotime($activeBaji->end_time)) }})
                    </span>
                @else
                    <span class="badge bg-danger fs-5 px-4 py-3">
                        No Active Baji Now
                    </span>
                @endif
            </div> --}}


            {{-- FORM --}}
            <form method="POST" action="{{ route('game-entry.store') }}">
                @csrf

                {{-- top controls --}}
                <div class="row g-3 mb-4 mt-1">

                    <div class="col-lg-4 col-md-4">
                        <div class="soft-box">
                            <label class="fw-bold mb-2">Baji</label>
                            <select name="baji" class="form-select">
                                @foreach($bajis as $baji)
                                    <option value="{{ $baji->id }}">
                                        {{ $baji->name }} - {{ $baji->end_time }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="soft-box">
                            <label class="fw-bold mb-3">Select Agent</label>

                            <div class="row mobile-scroll">
                                @foreach($agents as $agent)
                                <div class="col-lg-2 col-md-3 col-6 mb-2">
                                    <label class="select-card">
                                        <input type="radio"
                                               name="agent_id"
                                               value="{{ $agent->id }}"
                                               required>
                                        {{ $agent->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- type --}}
                <div class="soft-box mb-4">
                    <label class="fw-bold mb-3">Entry Type</label>

                    <div class="row">
                        <div class="col-6 col-md-3">
                            <label class="select-card w-100">
                                <input type="radio" name="type" value="single" required>
                                Single
                            </label>
                        </div>

                        <div class="col-6 col-md-3">
                            <label class="select-card w-100">
                                <input type="radio" name="type" value="patti" required>
                                Patti
                            </label>
                        </div>
                    </div>
                </div>

                {{-- entries --}}
                <div class="soft-box">
                    <label class="fw-bold mb-3">Numbers Entry</label>

                    <div id="entryRows">

                        <div class="entry-row">
                            <div class="row g-2 align-items-center">

                                <div class="col-md-8 col-12">
                                    <input type="text"
                                           name="numbers[]"
                                           class="form-control numberInput"
                                           placeholder="123.456.789">
                                </div>

                                <div class="col-md-3 col-9">
                                    <input type="number"
                                           name="amounts[]"
                                           class="form-control amountInput"
                                           placeholder="Amount">
                                </div>

                                <div class="col-md-1 col-3 text-end">
                                    <button type="button"
                                            class="btn btn-success add-btn addRow">
                                        +
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- sticky save --}}
                <div class="sticky-save mt-3 text-center">
                    <button class="btn btn-primary px-5 py-2 rounded-pill fw-bold">
                        SAVE ENTRY
                    </button>
                </div>

            </form>

        </div>
    </div>



    {{-- LAST 50 --}}
    <div class="card shadow-sm border-0">
        <div class="card-header">
            <h5 class="mb-0">Latest 50 Entries</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Time</th>
                            <th>Agent</th>
                            <th>Baji</th>
                            <th>Number</th>
                            <th>Amount</th>
                            <th>User</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($entries as $entry)
                            <tr>
                                <td>
                                    {{ $entry->created_at->format('d M h:i A') }}
                                </td>
                                <td>{{ $entry->agent->name }}</td>
                                <td>{{ $entry->baji->name }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $entry->game_number }}
                                    </span>
                                </td>
                                <td>₹{{ $entry->amount }}</td>
                                <td>{{ $entry->employee->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No entries found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('working');
        
        const entryRows = document.getElementById('entryRows');

        function getPlaceholder() {
            console.log('placeholder');
            
            let type = document.querySelector('input[name="type"]:checked')?.value;

            if (type === 'single') {
                return '1.2.3.4 or 1234';
            }

            if (type === 'patti') {
                return '123.456.789 or 123456789';
            }

            return 'Enter Number';
        }

        function createRow() {
            let html = `
                <div class="entry-row mb-2">
                    <div class="row g-2 align-items-center">

                        <div class="col-md-8 col-12">
                            <input type="text"
                                name="numbers[]"
                                class="form-control numberInput"
                                placeholder="${getPlaceholder()}"
                                autocomplete="off">
                        </div>

                        <div class="col-md-3 col-9">
                            <input type="number"
                                name="amounts[]"
                                class="form-control amountInput"
                                placeholder="Amount"
                                autocomplete="off">
                        </div>

                        <div class="col-md-1 col-3 text-end">
                            <button type="button"
                                    class="btn btn-danger removeRow">
                                ×
                            </button>
                        </div>

                    </div>
                </div>
            `;

            entryRows.insertAdjacentHTML('beforeend', html);

            let lastRow = entryRows.lastElementChild;
            lastRow.querySelector('.numberInput').focus();
        }

        function updatePlaceholder() {
            let placeholder = getPlaceholder();

            document.querySelectorAll('.numberInput').forEach(input => {
                input.placeholder = placeholder;
            });
        }

        function validateNumber(value) {
            let type = document.querySelector('input[name="type"]:checked')?.value;

            value = value.trim();

            if (!value) return false;

            if (type === 'single') {
                return /^[0-9.]+$/.test(value);
            }

            if (type === 'patti') {

                if (!/^[0-9.]+$/.test(value)) return false;

                if (value.includes('.')) {
                    let parts = value.split('.');
                    return parts.every(x => x.length === 3);
                } else {
                    return value.length % 3 === 0;
                }
            }

            return true;
        }

        document.addEventListener('change', function(e){
            if(e.target.name === 'type'){
                updatePlaceholder();
            }
        });

        document.addEventListener('click', function(e){

            if(e.target.classList.contains('addRow')){
                createRow();
            }

            if(e.target.classList.contains('removeRow')){
                e.target.closest('.entry-row').remove();
            }

        });

        document.addEventListener('keydown', function(e){

            if(e.key !== 'Enter') return;

            let target = e.target;

            if(target.classList.contains('numberInput')){
                e.preventDefault();

                if(!validateNumber(target.value)){
                    alert('Invalid number format');
                    target.focus();
                    return;
                }

                target
                    .closest('.entry-row')
                    .querySelector('.amountInput')
                    .focus();
            }

            else if(target.classList.contains('amountInput')){
                e.preventDefault();

                if(target.value === ''){
                    alert('Enter amount');
                    target.focus();
                    return;
                }

                createRow();
            }

        });

        updatePlaceholder();

        let first = document.querySelector('.numberInput');
        if(first) first.focus();

    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // elements
        const bajiSelect = document.querySelector('select[name="baji"]');
        const agentRadios = document.querySelectorAll('input[name="agent_id"]');
        const typeRadios = document.querySelectorAll('input[name="type"]');

        // -----------------------
        // Load saved values
        // -----------------------
        const savedBaji = localStorage.getItem('selected_baji');
        const savedAgent = localStorage.getItem('selected_agent');
        const savedType = localStorage.getItem('selected_type');

        if (savedBaji && bajiSelect) {
            bajiSelect.value = savedBaji;
        }

        if (savedAgent) {
            const agent = document.querySelector(
                `input[name="agent_id"][value="${savedAgent}"]`
            );
            if (agent) agent.checked = true;
        }

        if (savedType) {
            const type = document.querySelector(
                `input[name="type"][value="${savedType}"]`
            );
            if (type) type.checked = true;
        }

        // -----------------------
        // Save on change
        // -----------------------
        if (bajiSelect) {
            bajiSelect.addEventListener('change', function () {
                localStorage.setItem('selected_baji', this.value);
            });
        }

        agentRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                localStorage.setItem('selected_agent', this.value);
            });
        });

        typeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                localStorage.setItem('selected_type', this.value);
            });
        });

    });
</script>
@endsection