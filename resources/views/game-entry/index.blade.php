@extends('layouts.app')
@section('title','Game Entry')
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

<style>
.agent-corner-checkbox{
    position: absolute;
    top: 5px;
    right: 20px;
    z-index: 10;
}
.green-card{
    background: #28a745 !important;
    color: #fff !important;
    border-color: #28a745 !important;
}

.green-card *{
    color: #fff !important;
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

            {{-- FORM --}}
            <form method="POST" action="{{ route('game-entry.store') }}">
                @csrf

                {{-- top controls --}}
                <div class="row g-3 mb-4 mt-1">

                    <div class="col-lg-4 col-md-4">
                        <div class="soft-box">
                            <label class="fw-bold mb-2">Baji</label>
                            <select name="baji" class="form-select" id="baji_id" required>
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
                                <div class="col-lg-2 col-md-3 col-6 mb-2 position-relative">
                                    <label class="select-card agent-card">
                                        <input type="radio"
                                               name="agent_id"
                                               value="{{ $agent->id }}"
                                               required>
                                        {{ $agent->name }}
                                    </label>
                                    <input type="checkbox" name="agent_ids[]" value="{{ $agent->id }}" class="agent-corner-checkbox">
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

                        <div class="col-6 col-md-3">
                            <label class="select-card w-100">
                                <input type="radio" name="type" value="cp" required>
                                CP
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
        <div class="card-header d-flex flex-column align-items-center justify-content-center">
            <h5 class="mb-2">Latest 50 Entries</h5>
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#bulkEditModal">
                <i class="fa fa-edit"></i>
                Bulk Edit By Time
            </button>
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
                            <th>Action</th>
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
                                <td>
                                    <div class="d-flex gap-1">

                                        <button type="button"
                                                class="btn btn-sm btn-warning edit-btn"
                                                data-id="{{ $entry->id }}"
                                                data-agent="{{ $entry->agent_id }}"
                                                data-baji="{{ $entry->baji_id }}"
                                                data-number="{{ $entry->game_number }}"
                                                data-amount="{{ $entry->amount }}">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>

                                        <form action="{{ route('game-entry.destroy', $entry->id) }}" method="POST" class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="editEntryCanvas">
    <div class="offcanvas-header">
        <h5>Edit Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Agent</label>
                <select name="agent_id" id="edit_agent" class="form-select">
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Baji</label>
                <select name="baji_id" id="edit_baji" class="form-select">
                    @foreach($bajis as $baji)
                        <option value="{{ $baji->id }}">{{ $baji->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Game Number</label>
                <input type="text" name="game_number" id="edit_number" class="form-control">
            </div>

            <div class="mb-3">
                <label>Amount</label>
                <input type="number" name="amount" id="edit_amount" class="form-control">
            </div>

            <button class="btn btn-primary w-100">
                Update Entry
            </button>
        </form>
    </div>
</div>

<div class="modal fade" id="bulkEditModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Bulk Edit Entries
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                {{-- SEARCH --}}
                <div class="row g-2 mb-3">

                    <div class="col-md-5">
                        <input type="datetime-local"
                            id="bulk_from"
                            class="form-control"
                            value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="col-md-5">
                        <input type="datetime-local"
                            id="bulk_to"
                            class="form-control"
                            value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100"
                                id="loadBulkEntries">
                            Load
                        </button>
                    </div>

                </div>

                <form method="POST"
                      action="{{ route('game-entry.bulk-update') }}">

                    @csrf

                    {{-- CHANGE FOR ALL --}}
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label>Change Agent For Selected</label>

                            <select name="agent_id"
                                    class="form-select">

                                <option value="">Select Agent</option>

                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ $agent->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Change Baji For Selected</label>

                            <select name="baji_id"
                                    class="form-select">

                                <option value="">Select Baji</option>

                                @foreach($bajis as $baji)
                                    <option value="{{ $baji->id }}">
                                        {{ $baji->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                    {{-- ENTRY LIST --}}
                    <div class="table-responsive border rounded"
                        style="max-height: 500px; overflow-y: auto;">

                        <table class="table table-bordered table-hover mb-0">

                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>Time</th>
                                    <th>Agent</th>
                                    <th>Baji</th>
                                    <th>Number</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>

                            <tbody id="bulkTableBody">

                            </tbody>

                        </table>

                    </div>

                    <button class="btn btn-success">
                        Update Selected Entries
                    </button>

                </form>

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

            if (type === 'cp') {
                return '1234.45678.789789...';
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

            // if (type === 'cp') {
            //     // only digits and dot
            //     if (!/^[0-9.]+$/.test(value)) return false;

            //     // prevent invalid dot placement
            //     if (value.startsWith('.') || value.endsWith('.') || value.includes('..')) {
            //         return false;
            //     }

            //     let parts = value.split('.');

            //     // check each part
            //     return parts.every(part => {
            //         // length 4-7
            //         if (part.length < 4 || part.length > 7) return false;

            //         // ascending sequence check
            //         for (let i = 1; i < part.length; i++) {
            //             let prev = Number(part[i - 1]);
            //             let curr = Number(part[i]);

            //             let expected = (prev === 9) ? 0 : prev + 1;

            //             if (curr !== expected) {
            //                 return false;
            //             }
            //         }

            //         return true;
            //     });
            // }

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
<script>
    $(document).on('submit', '.delete-form', function(e){
        e.preventDefault();

        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: "This entry will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
<script>
    $(document).on('click', '.edit-btn', function () {

        let id = $(this).data('id');

        $('#edit_agent').val($(this).data('agent'));
        $('#edit_baji').val($(this).data('baji'));
        $('#edit_number').val($(this).data('number'));
        $('#edit_amount').val($(this).data('amount'));

        $('#editForm').attr('action', '/game-entry/' + id);

        let canvas = new bootstrap.Offcanvas('#editEntryCanvas');
        canvas.show();
    });
</script>

<script>

    $('#loadBulkEntries').click(function () {

        let from = $('#bulk_from').val();
        let to   = $('#bulk_to').val();

        $.get("{{ route('game-entry.bulk-list') }}", {
            from: from,
            to: to
        }, function (res) {

            let html = '';

            res.forEach(function (item) {

                html += `
                    <tr>

                        <td>
                            <input type="checkbox"
                                name="entry_ids[]"
                                value="${item.id}"
                                checked>
                        </td>

                        <td>
                            ${new Date(item.created_at).toLocaleString('en-IN', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            })}
                        </td>

                        <td>
                            ${item.agent?.name ?? ''}
                        </td>

                        <td>
                            ${item.baji?.name ?? ''}
                        </td>

                        <td>
                            ${item.game_number}
                        </td>

                        <td>
                            ₹${item.amount}
                        </td>

                    </tr>
                `;
            });

            $('#bulkTableBody').html(html);

        });

    });


    // CHECK ALL
    $(document).on('change', '#checkAll', function () {

        $('input[name="entry_ids[]"]')
            .prop('checked', $(this).prop('checked'));

    });

</script>

<script>
    $(document).on('change', '.agent-corner-checkbox', function() {

        let card = $(this).siblings('.agent-card');

        if ($(this).is(':checked')) {
            card.addClass('green-card');
        } else {
            card.removeClass('green-card');
        }

    });

    $(document).on('change', '.agent-corner-checkbox', function () {

        let checkbox = $(this);
        let card = checkbox.siblings('.agent-card');

        $.ajax({
            url: "{{ route('agent-green.toggle') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                agent_id: checkbox.val(),
                baji_id: $('#baji_id').val(), // selected baji
                status: checkbox.is(':checked') ? 1 : 0
            },
            success: function () {

                if (checkbox.is(':checked')) {
                    card.addClass('green-card');
                } else {
                    card.removeClass('green-card');
                }
            }
        });
    });

    function loadGreenAgents()
    {
        let bajiId = $('#baji_id').val();

        $('.agent-corner-checkbox').prop('checked', false);
        $('.agent-card').removeClass('green-card');

        $.get('/agent-green-status', {
            baji_id: bajiId
        }, function(agentIds) {

            agentIds.forEach(function(id) {

                let checkbox = $('.agent-corner-checkbox[value="'+id+'"]');

                checkbox.prop('checked', true);

                checkbox.siblings('.agent-card')
                        .addClass('green-card');
            });

        });
    }     
    
    $(document).ready(function() {
        loadGreenAgents();
    });

    $('#baji_id').on('change', function() {
        loadGreenAgents();
    });
</script>
@endsection