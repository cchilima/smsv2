@extends('layouts.master')
@section('page_title', Auth::user()->first_name . ' ' . Auth::user()->last_name . '\'s Results')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card overflow-scroll">
        <div class="card-header header-elements-inline">
            {{-- {!! Qs::getPanelOptions() !!} --}}
        </div>

        <div class="card-body">
            <div class="row justify-content-end">
                <div class="col-md-12">
                    <p>
                        This transcript may not include all courses required for your program completion.
                        Please verify with the Academics Office.
                    </p>
                </div>
            </div>
            <hr />
        </div>

        <div class="row ">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-md-12 p-3">
                        <h3>
                            {{ $student->program->name }} ({{ $student->program->code }})
                        </h3>

                        {{-- <p>{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }} </p> --}}

                        @foreach ($results as $innerIndex => $academicData)

                            <h5>
                                <strong>
                                    {{ $academicData['academic_period_name'] . ' (' . $academicData['academic_period_code'] . ')' }}
                                </strong>
                            </h5>

                            @if ($academicData['can_view_results'])
                                {{-- $academicPeriod?->academic_period_id == $academicData['academic_period_id'] && 
                                    $paymentPercentage >= $academicPeriod->view_results_threshold --}}
                                <table class="table table-hover table-striped-columns mb-3">
                                    {{-- <h5 class="p-2"><strong>{{ $student->id }}</strong></h5> --}}

                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Course Code</th>
                                            <th>Course Name</th>
                                            <th>Mark</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($academicData['grades']) > 0)
                                            @foreach ($academicData['grades'] as $course)
                                                <tr>
                                                    <th>{{ $loop->iteration }}</th>
                                                    <td>{{ $course['course_code'] }}</td>
                                                    <td>{{ $course['course_title'] }}</td>
                                                    <td>{{ $course['total'] }}</td>
                                                    <td>{{ $course['grade'] }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <p class="bg-info p-3 align-bottom">
                                                    Results for this academic period have not been published yet.
                                                </p>
                                            </tr>
                                        @endif
                                    </tbody>

                                </table>

                                @php
                                    $commentLower = str()->lower($academicData['comments']['comment']);

                                    $commentBgColor = match (true) {
                                        str()->startsWith($commentLower, 'proceed & repeat') => 'bg-warning',
                                        str()->startsWith($commentLower, 'part time') => 'bg-danger',
                                        default => 'bg-success',
                                    };
                                @endphp

                                <p class="{{ $commentBgColor }} p-3 align-bottom">Comment
                                    : {{ $academicData['comments']['comment'] }}</p>
                                <hr>
                            @else
                                <tbody>
                                    <tr>
                                        {{-- @if ($viewResultsBalance > 0 && !$canSeeResults) --}}
                                        <p class="bg-warning p-3 align-bottom">
                                            Clear your balance of
                                            <strong>K{{ $academicData['view_results_balance'] }}</strong> to
                                            view results for this academic period.
                                        </p>
                                    </tr>
                                </tbody>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
