@extends('layouts.app')
@section('title','Patti Entry')
@section('style')

@endsection

@section('content')

<div class="container-fluid">


    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">Patti Entry</h5>
        </div>

        <div class="card-body">

            {{-- FORM --}}
            <form method="POST" action="{{ route('patti-check.store') }}">
                @csrf

                {{-- entries --}}
                <div class="soft-box">
                    <label class="fw-bold mb-3 mt-3">Numbers Entry</label>

                    <div id="entryRows">

                        <div class="entry-row">
                            <div class="row g-2 align-items-center">

                                <div class="col-md-12 col-12">
                                    <input type="text"
                                           name="numbers"
                                           class="form-control numberInput"
                                           placeholder="123.456.789">
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

</div>
@endsection

@section('script')

@endsection