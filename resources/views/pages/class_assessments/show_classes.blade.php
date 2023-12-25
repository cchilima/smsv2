@extends('layouts.master')
@section('page_title', 'Manage Class Assessment for ')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Class Assessment And Exams Manager</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Course Name</th>
                    <th>Code</th>
                    <th>Instructor</th>
                    <th>Students</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($apClasses->classes as $classAssessment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $classAssessment->course->name }}</td>
                        <td>{{ $classAssessment->course->code }}</td>
                        <th>{{ $classAssessment->instructor->first_name.' '.$classAssessment->instructor->last_name }}</th>
                        <td>{{ '' }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left">
                                    @foreach($classAssessment->class_assessments as $assess)
                                            <a href="{{ route('myClassStudentList', ['class' => Qs::hash($classAssessment->id),'assessid' => Qs::hash($assess->assessment_type->id)]) }}"
                                               class="dropdown-item"><i class="icon-eye"></i>Enter {{ $assess->assessment_type->name }} Results</a>
                                    @endforeach
                                    </div>
{{--                                    <div class="dropdown-menu dropdown-menu-left">--}}
{{--                                        <a href="{{ route('myClassStudentList', Qs::hash($classAssessment->id)) }}"--}}
{{--                                           class="dropdown-item"><i class="icon-eye"></i> View Students</a>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{--Student List Ends--}}

@endsection
