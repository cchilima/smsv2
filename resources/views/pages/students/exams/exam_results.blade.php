@extends('layouts.master')
@section('page_title', Auth::user()->first_name .'\'s Results')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card overflow-scroll">
        <div class="card-header header-elements-inline">
            {{--            {!! Qs::getPanelOptions() !!}--}}
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
            <hr/>
        </div>

        <div class="row ">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-md-12 p-3">
                        <h3>Program: {{ $student->program->name }} ({{ $student->program->code }}
                            )</h3>
                        <p>{{Auth::user()->first_name.' '.Auth::user()->last_name }}  </p>
                        @foreach ($results as $innerIndex =>$academicData)
                            <table class="table table-hover table-striped-columns mb-3">
                                <h5 class="p-2">
                                    <strong>{{ $academicData['academic_period_code'] .' - '.$academicData['academic_period_name'] }}</strong>
                                </h5>
                                <h5 class="p-2"><strong>{{ $student->id }}</strong></h5>
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
                                @foreach ($academicData['grades'] as $course)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $course['course_code'] }}</td>
                                        <td>{{ $course['course_title'] }}</td>
                                        <td> {{ $course['total']  }}</td>
                                        <td>{{ $course['grade']  }}</td>
                                    </tr>

                                @endforeach
                                </tbody>

                            </table>
                            <p class="bg-success p-3 align-bottom">Comment
                                : {{ $academicData['comments']['comment'] }}</p>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
