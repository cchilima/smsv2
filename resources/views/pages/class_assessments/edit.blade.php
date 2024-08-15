@extends('layouts.master')
@section('page_title', 'Publishing Results for ' . $period->code)
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
            <a href="{{ route('PublishForAllStudents', ['ac' => $period->id, 'type' => 1]) }}" class="dropdown-item"><i
                    class="icon-eye"></i> Publish Results for {{ $period->code }}</a>

            <livewire:datatables.academics.assessments.publish-results :academicPeriodId="$id" />
        </div>

    </div>
@endsection
