@extends('layouts.master')
@section('page_title', 'Class Results Entry Form')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h3>{{ $class_ass->class_assessments[0]->assessment_type->name }}</h3>
            <h6 class="card-title">Enter {{ $class_ass->class_assessments[0]->assessment_type->name }}  Results
                for {{ $class_ass->course->code.' - '.$class_ass->course->name }}</h6>
            <h6 class="card-title assess-total">Being Marked out of {{ $class_ass->class_assessments[0]->total }}</h6>
            <input type="hidden" name="course_id" value="{{ Qs::hash($class_ass->course_id) }}">
            <input type="hidden" name="ac_id" value="{{ Qs::hash($class_ass->academic_period_id) }}">
            <input type="hidden" name="assess_type_id" value="{{ Qs::hash($class_ass->class_assessments[0]->assessment_type_id) }}">
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#ut-post-results" class="nav-link active" data-toggle="tab"><i
                                class="icon-plus2"></i>Enter results</a></li>
                <li class="nav-item"><a href="#Upload-results"
                                        class="nav-link {{ (!empty($isInstructor) && $isInstructor == 1)? 'active' :'' }}"
                                        data-toggle="tab"><i
                                class="icon-plus2"></i>Post results</a></li>
{{--                                <li class="nav-item"><a href="#post-results"--}}
{{--                                                        class="nav-link "--}}
{{--                                                        data-toggle="tab"><i--}}
{{--                                                class="icon-plus2"></i>Post results</a></li>--}}
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="ut-post-results">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Assessment Type</th>
                            <th>Marks</th>
                            {{--                            <th>Action</th>--}}
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($class_ass->enrollments as $enroll)
                            <tr>
{{--                                @dd($enroll->student->grades[0])--}}
{{--                                @if($enroll->student && $class_ass->course->code == $enroll->student->grades[0]->course_code)--}}
                                    @if(isset($enroll->student))
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $enroll->student->user->first_name.' '.$enroll->student->user->last_name }}</td>
                                <td>{{ $enroll->student->id }}</td>
                                <td>{{ $class_ass->class_assessments[0]->assessment_type->name }}</td>
                                <td class="edit-total-link">

                                    @if(!empty($enroll->student->grades[0]))
{{--                                    <input type="hidden" id="course{{ Qs::hash($classAssessment['student_id']) }}"--}}
{{--                                           value="{{ $class[0]['courseCode'] }}">--}}
{{--                                    <input type="hidden" id="title{{ Qs::hash($classAssessment['student_id']) }}"--}}
{{--                                           value="{{ $class[0]['courseName'] }}">--}}
{{--                                    <input type="hidden" id="idc{{ Qs::hash($classAssessment['student_id']) }}"--}}
{{--                                           value="{{ $class[0]['classID'] }}">--}}
{{--                                    <input type="hidden" id="program{{ Qs::hash($classAssessment['student_id']) }}"--}}
{{--                                           value="{{ $classAssessment['program'] }}">--}}
{{--                                    <input type="hidden" id="apid{{ Qs::hash($classAssessment['student_id']) }}"--}}
{{--                                           value="{{ $class[0]['apid'] }}">--}}
{{--                                    <input type="hidden" id="assessid{{ Qs::hash($classAssessment['student_id']) }}"--}}
{{--                                           value="{{ $class[0]['assessmentId'] }}">--}}
                                    <input type="hidden" id="gradeid{{ Qs::hash($enroll->student->id) }}"
                                           value="{{ $enroll->student->grades[0]->id}}">
                                    <span class="display-mode"
                                          id="display-mode{{ Qs::hash($enroll->student->id) }}">{{ $enroll->student->grades[0]->total }}</span>
                                    <input type="text" class="edit-mode form-control"
                                           id="class{{ Qs::hash($enroll->student->id) }}"
                                           value="{{ $enroll->student->grades[0]->total  }}" style="display: none;"
                                           onchange="EnterResults('{{Qs::hash($enroll->student->id)}}','{{$class_ass->class_assessments[0]->total}}',1)">
                                    @else
                                        <input type="hidden" id="gradeid{{ Qs::hash($enroll->student->id) }}"
                                               value="0">
                                        <span class="display-mode"
                                              id="display-mode{{ Qs::hash($enroll->student->id) }}">NE</span>
                                        <input type="text" class="edit-mode form-control"
                                               id="class{{ Qs::hash($enroll->student->id) }}"
                                               value="0" style="display: none;"
                                               onchange="EnterResults('{{Qs::hash($enroll->student->id)}}','{{$class_ass->class_assessments[0]->total}}',0)">
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade show "
                     id="post-results">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Add Student Results
                                </div>
                                <div class="card-body">

                                    <!-- Import Form -->
                                    <form method="POST" action="{{ route('import.process') }}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold"
                                                   for="nal_id">Academic Period: <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select onchange="getRunningPrograms(this.value)"
                                                        data-placeholder="Choose..." name="academic" required
                                                        id="nal_id" class="select-search form-control">
                                                    <option value="">Choose</option>
                                                    <option value="{{ Qs::hash($class_ass->academic_period_id ) }}">{{ $class_ass->academicPeriod->code  }}</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="classID"
                                                   class="col-lg-3 col-form-label font-weight-semibold">Class: <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select data-placeholder="Choose..." required name="programID"
                                                        id="classID" class=" select-search form-control">
                                                    <option value="">Choose</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="classID"
                                                   class="col-lg-3 col-form-label font-weight-semibold">Choose File
                                                <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <input type="file" class="form-control-file" id="file" name="file"
                                                       required>
                                                <input type="hidden" name="instructor" value="instructorav"
                                                       required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Results</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show {{ (!empty($isInstructor) && $isInstructor == 1)? 'active' :'' }}"
                     id="Upload-results">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Import CSV or Excel File
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if(empty($data))
                                        <!-- Import Form -->
                                        <form method="POST" action="{{ route('import.process') }}"
                                              enctype="multipart/form-data">
                                            @csrf

                                            <input type="hidden" name="academic" value="{{ $class_ass->academic_period_id  }}">
                                            <input type="hidden" name="course" value="{{ $class_ass->course->code }}">
                                            <input type="hidden" name="title" value="{{ $class_ass->course->name }}">
                                            <input type="hidden" name="assesTotal" value="{{  $class_ass->class_assessments[0]->total }}">

                                            <div class="form-group row">
                                                <label for="classID"
                                                       class="col-lg-3 col-form-label font-weight-semibold">Choose File
                                                    <span class="text-danger">*</span></label>
                                                <div class="col-lg-9">
                                                    <input type="file" class="form-control-file" id="file" name="file"
                                                           required>
                                                    <input type="hidden" name="instructor" value="instructorav"
                                                           required>
                                                </div>
                                            </div>
                                            <input type="hidden" id="idc" name="AssessIDTemplate"
                                                   value="{{ $class_ass->class_assessments[0]->assessment_type_id }}">
                                            <input type="hidden" id="idc" name="backroute"
                                                   value="{{ $class_ass->class_assessments[0]->assessment_type_id }}">
                                            <input type="hidden" id="assessid" name="classIDTemplate"
                                                   value="{{ $class_ass->id }}">
                                            <button type="submit" class="btn btn-primary">Upload and Preview</button>
                                            <button type="button" onclick="downloadCSVtemplate()" class="btn btn-primary">Download CSV Template</button>
                                        </form>
                                    @else
                                        <!-- Data Preview Table -->
                                        <h2>Results Preview</h2>
                                        <table class="table table-bordered table-hover datatable-button-html5-columns">
                                            <thead>
                                            <tr>
                                                                                            @foreach($data[0] as $column => $value)
                                                                                                <th>{{ $column }}</th>
                                                                                            @endforeach
                                                <th> SIN</th>
                                                <th> CODE</th>
                                                <th> COURSE</th>
                                                <th> MARK</th>
                                                <th> ACADEMIC PERIOD</th>
                                                <th> PROGRAM</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data as $row)
                                                <tr>
                                                    @foreach($row as $value)
                                                        <td>{{ $value }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <!-- Import Button -->
                                        <div class="row col mb-4 mt-3">
                                            <form method="POST" action="{{ route('import.process') }}"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="file" class="form-control-file" id="file" name="file"
                                                           required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Import Data</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{--Class List Ends--}}

@endsection

