@if (!empty($grades))
    @section('page_title', $period->name . 's Results')
@else
    @section('page_title', 'No results found')
@endif

@php
    use App\Helpers\Qs;
@endphp

<div class="card overflow-scroll">
    <div class="card-header header-elements-inline">
        {{--            {!! Qs::getPanelOptions() !!} --}}
    </div>

    <div class="card-body">
        {{--            <div class="row justify-content-end"> --}}
        {{--                <div class="col-md-12"> --}}
        {{--                    <p> --}}
        {{--                        These results may not include all courses required for program completion. --}}
        {{--                    </p> --}}
        {{--                </div> --}}
        {{--            </div> --}}
        {{--            <hr/> --}}
        {{--        </div> --}}
        <div class="row p-3">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-md-12">
                        @if (!empty($grades['students']))
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
                                <label for="assesmentID"
                                    class="col-lg-3 col-form-label font-weight-semibold">Course(Moderate
                                    for all): <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    @php
                                        $uniqueCourseCodes = [];
                                    @endphp
                                    <select data-placeholder="Choose..." required name="assesmentID" id="assesmentID"
                                        class=" select-search form-control" onchange="StrMod4All(this.value,1)">
                                        <option value=""></option>
                                        @foreach ($grades['students'] as $student)
                                            @foreach ($student['courses'] as $course)
                                                {{--                                                    @foreach ($course['course_details']['course_code'] as $course) --}}
                                                @php
                                                    $code = $course['course_details']['course_code'];
                                                    $title = $course['course_details']['course_title'];
                                                    $optionValue = $code . ' - ' . $title;
                                                @endphp
                                                @if (!in_array($optionValue, $uniqueCourseCodes))
                                                    <option value="{{ $course['course_details']['class_id'] }}">
                                                        {{ $optionValue }}</option>
                                                    @php
                                                        $uniqueCourseCodes[] = $optionValue;
                                                    @endphp
                                                @endif
                                                {{--                                                    @endforeach --}}
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>

                            <div class="loading-more-results" style="">
                                <div style="">
                                    @livewire('datatables.academics.assessments.exam-results-review-board', [
                                        'level' => $level,
                                        'program' => $program_data,
                                        'academicPeriod' => $period,
                                    ])

                                    <h1 class="mt-4 mb-4 text-info">OLD TABLE</h1>
                                    <hr>

                                    @foreach ($grades['students'] as $student)
                                        <table class="table table-hover table-striped-columns mb-3">
                                            <div class="justify-content-between">
                                                <h5>
                                                    <strong>{{ $student['name'] }}</strong>
                                                    <p>{{ $student['id'] }}</p>
                                                </h5>
                                                <h5><strong>{{ $student['id'] }}</strong></h5>
                                                <input type="hidden" name="academic" value="{{ $period->id }}">
                                                <input type="hidden" name="program" value="{{ $program_data->id }}">
                                                <input type="hidden" name="level_name"
                                                    value="{{ $program_data->id }}">
                                                <input type="hidden" name="level_name"
                                                    value="{{ $student['course_level_id'] }}">
                                                <input type="hidden" name="type" value="1">
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
                                                @foreach ($student['courses'] as $course)
                                                    <tr>
                                                        <th>{{ $loop->iteration }}</th>
                                                        <td>{{ $course['course_details']['course_code'] }}</td>
                                                        <td>{{ $course['course_details']['course_title'] }}</td>
                                                        <td>
                                                            <table
                                                                class="table table-bordered table-hover table-striped">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>CA</td>
                                                                        <td>Exam</td>
                                                                        <td>Total</td>
                                                                        <td>Grade</td>
                                                                    </tr>
                                                                    {{--                                                            @foreach ($courses->class->course->grades as $grade) --}}
                                                                    @foreach ($course['course_details']['student_grades'] as $grade)
                                                                        <tr>
                                                                            <td>{{ $grade['ca'] }}</td>
                                                                            <td>{{ $grade['exam'] . ' out of ' . $grade['outof'] }}
                                                                            </td>
                                                                            <td>{{ $grade['total_sum'] }}</td>
                                                                            <td>{{ $grade['grade'] }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td>
                                                            @if (Qs::userIsTeamSA())
                                                                <a onclick="modifyMarksExam('{{ $student['id'] }}','{{ $student['name'] }}','{{ $course['course_details']['course_code'] }}','{{ $course['course_details']['course_title'] }}','{{ json_encode($course['course_details']['student_grades']) }}')"
                                                                    class="nav-link"><i class="icon-pencil"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <p class="bg-success p-4 align-bottom">
                                            {{--                                            @if ($student->calculated_grade['student_id'] == $student->id) --}}
                                            {{ $student['calculated_grade']['comment'] }}
                                            {{--                                            @endif --}}

                                            {{ Form::checkbox('ckeck_user', 1, false, ['class' => 'ckeck_user  float-right p-5', 'data-id' => $student['id']]) }}
                                            {{ Form::label('publish', 'Publish', ['class' => 'mr-3 float-right']) }}
                                        </p>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary publish-results-board btn-sm mt-3"
                                    disabled="disabled"><i class="fa fa-share"></i> Publish Results
                                </button>
                                @if ($grades['current_page'] === $grades['last_page'])
                                @else
                                    <button type="button"
                                        class="float-right mr-5 btn btn-primary load-more-results load-more-results-first btn-sm mt-3"
                                        onclick="LoadMoreResults('{{ $grades['current_page'] }}','{{ $grades['last_page'] }}','{{ $grades['per_page'] }}','{{ $program_data->id }}','{{ $period->id }}','{{ $student['course_level_id'] }}')">
                                        <i class="fa fa-share"></i> Load More
                                    </button>
                                @endif

                                <p class="text-center" id="pagenumbers">page {{ $grades['current_page'] }}
                                    of {{ $grades['last_page'] }}</p>

                            </div>
                        @else
                            <h3>Results not found</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
