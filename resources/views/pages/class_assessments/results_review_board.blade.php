@extends('layouts.master')
@if (!empty($results))
    @php
        $firstAcademicData = reset($results);
        $academicData = $firstAcademicData['academic'];
    @endphp
    @section('page_title', $firstAcademicData['academicperiodname'] .'s Results')

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
                            @if (!empty($results))
                                <div class="d-flex justify-content-between align-items-center float-right">
                                    <label class="mb-2">
                                        Publish All <input type="checkbox" value="1" name="user-all"
                                                           class="user-all form-check">
                                    </label>
                                </div>
                                <h3>Program: {{ $firstAcademicData['program_name'] }}
                                    ({{ $firstAcademicData['program_code'] }}
                                    )</h3>
                                <h4>{{ $firstAcademicData['level_name'] }}'s Results</h4>
                                <h4 class="mb-4 mt-0">Results for {{ $firstAcademicData['total_students'] }}
                                    Students</h4>
                                <div class="row">
                                    <label for="assesmentID" class="col-lg-3 col-form-label font-weight-semibold">Course(Moderate
                                        for all): <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        @php
                                            $uniqueCourseCodes = [];
                                        @endphp
                                        <select data-placeholder="Choose..." required name="assesmentID"
                                                id="assesmentID" class=" select-search form-control"
                                                onchange="StrMod4All('{{$firstAcademicData['program']}}','{{$firstAcademicData['academic']}}', this.value)">
                                            <option value=""></option>
                                            @foreach ($results as $academicData)
                                                @foreach ($academicData['students'] as $student)
                                                    @foreach($student['courses'] as $course)
                                                        @php
                                                            $code = $course['code'];
                                                            $title = $course['title'];
                                                            $optionValue = $code . ' - ' . $title;
                                                        @endphp
                                                        @if (!in_array($optionValue, $uniqueCourseCodes))
                                                            <option value="{{ $code }}">{{ $optionValue }}</option>
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
                                @foreach ($results as $academicData)
                                    <div style="height: 800px;overflow-y: scroll">
                                        @foreach ($academicData['students'] as $student)
                                            <table class="table table-hover table-striped-columns mb-3">
                                                <div class="justify-content-between">
                                                    <h5><strong>{{ $student['name'] }}</strong></h5>
                                                    <h5><strong>{{ $student['student_id'] }}</strong></h5>
                                                    <input type="hidden" name="academic"
                                                           value="{{ $firstAcademicData['academic'] }}">
                                                    <input type="hidden" name="program"
                                                           value="{{ $firstAcademicData['program'] }}">
                                                    <input type="hidden" name="level_name"
                                                           value="{{ $firstAcademicData['level_id'] }}">
                                                    <input type="hidden" name="typepublish"
                                                           value="Exam">
                                                </div>

                                                <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Course Code</th>
                                                    <th>Course Name</th>
                                                    <th>CA</th>
                                                    <th>Exam</th>
                                                    <th>Total</th>
                                                    <th>Grade</th>
                                                    <th>Modify</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($student['courses'] as $course)
                                                    <tr>
                                                        <th>{{ $loop->iteration }}</th>
                                                        <td>{{ $course['code'] }}</td>
                                                        <td>{{ $course['title'] }}</td>
                                                        <td>{{ $course['CA']  }}</td>
                                                        <td>
                                                            @foreach($course['assessments'] as $assess)
                                                                @if(!empty($assess['assessment_name']) && $assess['assessment_name']=='Exam')
                                                                    {{ $assess['total'] }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        {{--                                        <td>{{ $course['CA'] }}</td>--}}
                                                        <td>{{ $course['total'] }}</td>
                                                        <td>{{ $course['grade'] }}</td>
                                                        <td>
                                                            @if(Qs::userIsTeamSA())
                                                                <a onclick="modifyMarks('{{$student['student_id']}}','{{$firstAcademicData['program']}}','{{$firstAcademicData['academic']}}','{{ $course['code'] }}','exam')"
                                                                   class="nav-link"><i class="icon-pencil"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>

                                            </table>
                                            <p class="bg-success p-3 align-bottom">Comment
                                                : {{ $student['commentData'] }}
                                                {{ Form::checkbox('ckeck_user', 1, false,['class'=>'ckeck_user  float-right p-5','data-id' => $student['student_id'] ]) }} {{ Form::label('publish', 'Publish', ['class' => 'mr-3 float-right']) }}</p>
                                            <hr>
                                        @endforeach

                                    </div>
                                @endforeach
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary publish-results-board btn-sm mt-3"
                                        disabled="disabled"><i class="fa fa-share"></i> Publish Results
                                </button>
                                @if($firstAcademicData['current_page'] === $firstAcademicData['last_page'])

                                @else
                                    <button type="button" class="float-right mr-5 btn btn-primary load-more-results load-more-results-first btn-sm mt-3"
                                    onclick="LoadMoreResults('{{ $firstAcademicData['current_page'] }}','{{ $firstAcademicData['last_page'] }}','{{ $firstAcademicData['per_page'] }}','{{$firstAcademicData['program']}}','{{$firstAcademicData['academic']}}','{{$firstAcademicData['level_id']}}')">
                                    <i class="fa fa-share"></i> Load More
                                    </button>
                                @endif


                                <p class="text-center" id="pagenumbers">page {{ $firstAcademicData['current_page'] }}
                                    of {{ $firstAcademicData['last_page'] }}</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content row col card card-body">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        {{--                        <div class="list-icons">--}}
                        {{--                            <a class="list-icons-item closeModalButton" onclick="modifyMarksCloseModal()"--}}
                        {{--                               data-action="remove"></a>--}}
                        {{--                        </div>--}}
                    </div>
                    <div class="modal-body p-3">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closeModalButton"
                                onclick="modifyMarksCloseModal()"
                                id="closeModalButton" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" id="submitButton" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

