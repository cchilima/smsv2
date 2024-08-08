@extends('layouts.master')
@section('page_title', 'Manage Class Assessments & Exams')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Class Assessments & Exams</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight" role="tablist" id="myTabs">
                <li class="nav-item">
                    <a href="#all-class-lists" class="nav-link active" data-toggle="tab">Class Lists</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-class-lists">
                    <livewire:datatables.academics.assessments.class-lists />
                </div>
            </div>
        </div>
    </div>

    {{-- Class Assessment List Ends --}}

@endsection
