@extends('layouts.master')
@section('page_title', 'Publishing Results for ' . $academicPeriod->code)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Publish Results</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <a href="{{ route('PublishForAllStudents', ['ac' => $academicPeriod->id, 'type' => 1]) }}"
                class="dropdown-item"><i class="icon-eye"></i> Publish Results for {{ $academicPeriod->code }}</a>

            @livewire('datatables.academics.assessments.publish-results', [
                'academicPeriodId' => $academicPeriod->id,
            ])
        </div>

    </div>
@endsection
