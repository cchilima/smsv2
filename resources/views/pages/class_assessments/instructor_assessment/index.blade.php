@extends('layouts.master')
@section('page_title', 'Class Results Entry Form')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h3>{{ $class[0]['code'] }}</h3>
            <h6 class="card-title">Enter Assessment And Exam Results
                for {{ $class[0]['courseCode'].' - '.$class[0]['courseName'] }}</h6>
            <h6 class="card-title assess-total">Being Marked out of {{ $class[0]['assess_total'] }}</h6>
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
                {{--                <li class="nav-item"><a href="#post-results"--}}
                {{--                                        class="nav-link "--}}
                {{--                                        data-toggle="tab"><i--}}
                {{--                                class="icon-plus2"></i>Post results</a></li>--}}
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

                        @foreach($class[0]['students'] as $classAssessment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $classAssessment['first_name'].' '.$classAssessment['last_name'] }}</td>
                                <td>{{ $classAssessment['student_id'] }}</td>
                                <td>{{ $class[0]['assessmentName'] }}</td>
                                <td class="edit-total-link">
                                    <input type="hidden" id="course{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $class[0]['courseCode'] }}">
                                    <input type="hidden" id="title{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $class[0]['courseName'] }}">
                                    <input type="hidden" id="idc{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $class[0]['classID'] }}">
                                    <input type="hidden" id="program{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $classAssessment['program'] }}">
                                    <input type="hidden" id="apid{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $class[0]['apid'] }}">
                                    <input type="hidden" id="assessid{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $class[0]['assessmentId'] }}">
                                    <input type="hidden" id="userid{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $classAssessment['userID'] }}">
                                    <span class="display-mode"
                                          id="display-mode{{ Qs::hash($classAssessment['student_id']) }}">{{ $classAssessment['total'] }}</span>
                                    <input type="text" class="edit-mode form-control"
                                           id="class{{ Qs::hash($classAssessment['student_id']) }}"
                                           value="{{ $classAssessment['total'] }}" style="display: none;"
                                           onchange="EnterResults('{{Qs::hash($classAssessment['student_id'])}}')">
                                </td>


                                {{--                                <td class="text-center">--}}
                                {{--                                    <a href="#" class="edit-total-link"><i--}}
                                {{--                                                class="icon-pencil"></i></a>--}}
                                {{--                                    <div class="list-icons">--}}
                                {{--                                        <div class="dropdown">--}}
                                {{--                                            <a href="#" class="list-icons-item" data-toggle="dropdown">--}}
                                {{--                                                <i class="icon-menu9"></i>--}}
                                {{--                                            </a>--}}

                                {{--                                            <div class="dropdown-menu dropdown-menu-left">--}}
                                {{--                                                <a href="#" class="dropdown-item edit-total-link"><i--}}
                                {{--                                                            class="icon-pencil"></i> Edit</a>--}}

                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </td>--}}
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
                                                    <option value="{{ Qs::hash($class[0]['apid'] ) }}">{{ $class[0]['code']  }}</option>

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

                                            <input type="hidden" name="academic" value="{{ $class[0]['apid']  }}">
                                            <input type="hidden" name="course" value="{{ $class[0]['courseCode'] }}">
                                            <input type="hidden" name="title" value="{{ $class[0]['courseName'] }}">
                                            <input type="hidden" name="assesTotal" value="{{  $class[0]['assess_total'] }}">

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
                                                   value="{{ $class[0]['assessmentId'] }}">
                                            <input type="hidden" id="assessid" name="classIDTemplate"
                                                   value="{{ $class[0]['classID'] }}">
                                            <button type="submit" class="btn btn-primary">Upload and Preview</button>
                                            <button type="button" onclick="downloadCSVtemplate()" class="btn btn-primary">Download CSV Template</button>
                                        </form>
                                    @else
                                        <!-- Data Preview Table -->
                                        <h2>Results Preview</h2>
                                        <table class="table table-bordered table-hover datatable-button-html5-columns">
                                            <thead>
                                            <tr>
                                                {{--                                                @foreach($data[0] as $column => $value)--}}
                                                {{--                                                    <th>{{ $column }}</th>--}}
                                                {{--                                                @endforeach--}}
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

