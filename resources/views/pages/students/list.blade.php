@extends('layouts.master')
@section('page_title', 'Student List')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Student List</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-settings" class="nav-link active" data-toggle="tab">Student List</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-settings">
                    <livewire:datatables.admissions.students.students />
                </div>
            </div>
        </div>
    </div>

    {{-- Student List Ends --}}
@endsection
