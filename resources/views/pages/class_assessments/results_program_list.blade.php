@extends('layouts.master')
@section('page_title', 'Program results')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Download Student Results per Program</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <livewire:datatables.academics.assessments.program-list :academicPeriodId="$academicPeriodId" />
        </div>

    </div>

    {{-- Student Results Program List Ends --}}

@endsection
