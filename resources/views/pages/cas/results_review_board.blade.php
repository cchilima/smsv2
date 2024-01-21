@extends('layouts.master')
@if (!empty($grades))
    @section('page_title', $period->name .'s Results')
@else
    @section('page_title', 'No results found')

@endif
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card overflow-scroll">
        <div class="card-header header-elements-inline">
            {{--            {!! Qs::getPanelOptions() !!}--}}
        </div>

        <div class="card-body">
            {{--            <div class="row justify-content-end">--}}
            {{--                <div class="col-md-12">--}}
            {{--                    <p>--}}
            {{--                        These results may not include all courses required for program completion.--}}
            {{--                    </p>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--            <hr/>--}}
            {{--        </div>--}}
            <div class="row p-3">
                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-md-12">
                            @if (!empty($grades))
                                <div class="d-flex justify-content-between align-items-center float-right">
                                    <label class="mb-2">
                                        Publish All <input type="checkbox" value="1" name="user-all"
                                                           class="user-all form-check">
                                    </label>
                                </div>
                                <h3>Program: {{ $program_data->name }}
                                    ({{ $program_data->code }}
                                    )</h3>
                                <h4>{{ $level->name }}'s Results</h4>
                                <h4 class="mb-4 mt-0">Results for {{ $students }}
                                    Students out</h4>
                                <div class="row">
                                    <label for="assesmentID" class="col-lg-3 col-form-label font-weight-semibold">Course(Moderate
                                        for all): <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        @php
                                            $uniqueCourseCodes = [];
                                        @endphp
                                        <select data-placeholder="Choose..." required name="assesmentID"
                                                id="assesmentID" class=" select-search form-control"
                                                onchange="StrMod4All(this.value,0)">
                                            <option value=""></option>
                                            @foreach ($grades as $student)
                                                @foreach($student->enrollments as $courses)
                                                    @foreach($courses->class->course->grades as $course)
                                                        @php
                                                            $code = $course->course_code;
                                                            $title = $course->course_title;
                                                            $optionValue = $code . ' - ' . $title;
                                                        @endphp
                                                        @if (!in_array($optionValue, $uniqueCourseCodes))
                                                            <option value="{{ $courses->class->id }}">{{ $optionValue }}</option>
                                                            @php
                                                                $uniqueCourseCodes[] = $optionValue;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>

                            @else
                                <h3>Results not found</h3>
                            @endif
                            <div class="loading-more-results pr-4" style="height: 600px; overflow-y: scroll">
                                <div style="height: 800px;overflow-y: scroll">
                                    @foreach ($grades as $student)
                                        <table class="table table-hover table-striped-columns mb-3">
                                            <div class="justify-content-between">
                                                <h5>
                                                    <strong>{{ $student->user->first_name.' '.$student->user->last_name }}</strong>
                                                </h5>
                                                <h5><strong>{{ $student->student_id }}</strong></h5>
                                                <input type="hidden" name="academic"
                                                       value="{{ $period->id }}">
                                                <input type="hidden" name="program"
                                                       value="{{ $program_data->id }}">
                                                <input type="hidden" name="level_name"
                                                       value="{{ $program_data->id }}">
                                                <input type="hidden" name="level_name"
                                                       value="{{ $student->course_level_id }}">
                                                <input type="hidden" name="type"
                                                       value="0">
                                            </div>

                                            <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Course Code</th>
                                                <th>Course Name</th>

                                                <th>Assessments</th>
                                                <th>Modify</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($student->enrollments as $courses)
                                                <tr>
                                                    <th>{{ $loop->iteration }}</th>
                                                    <td>{{ $courses->class->course->code  }}</td>
                                                    <td>{{ $courses->class->course->name  }}</td>
                                                    <td>
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <tbody>
                                                            @foreach ($courses->class->course->grades as $grade)
                                                                <tr>
                                                                    <td>{{ $grade->assessment_type->name }}</td>
                                                                    <td>{{ $grade->total }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        @if(Qs::userIsTeamSA())
                                                            <a onclick="modifyMarks('{{ $student->id }}','{{ $student->user }}','{{ $courses->class->course->code }}','{{ $courses->class->course->name }}','{{ $courses->class->course->grades }}')"
                                                               class="nav-link"><i class="icon-pencil"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>

                                        </table>
                                        <p class="bg-success p-4 align-bottom">
                                            {{ Form::checkbox('ckeck_user', 1, false,['class'=>'ckeck_user  float-right p-5','data-id' => $student->id ]) }} {{ Form::label('publish', 'Publish', ['class' => 'mr-3 float-right']) }}</p>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary publish-results-board btn-sm mt-3"
                                        disabled="disabled"><i class="fa fa-share"></i> Publish Results
                                </button>
                                @if($grades->currentPage() === $grades->lastPage())

                                @else
                                    <button type="button"
                                            class="float-right mr-5 btn btn-primary load-more-results load-more-results-first btn-sm mt-3"
                                            onclick="LoadMoreResultsCas('{{ $grades->currentPage() }}','{{ $grades->lastPage() }}','{{ $grades->perPage() }}','{{$program_data->id}}','{{ $period->id }}','{{ $student->course_level_id }}')">
                                        <i class="fa fa-share"></i> Load More
                                    </button>
                                @endif


                                <p class="text-center" id="pagenumbers">page {{ $grades->currentPage() }}
                                    of {{ $grades->lastPage() }}</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

