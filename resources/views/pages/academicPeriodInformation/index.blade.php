@extends('layouts.master')
@section('page_title', 'Manage - '.$academic->name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Academic Period</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#new-period" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New Academic Period</a></li>
                <li class="nav-item"><a href="#all-fees" class="nav-link" data-toggle="tab"><i
                            class="icon-plus2"></i> Create Classes</a></li>
                <li class="nav-item"><a href="#new-ac-fees" class="nav-link" data-toggle="tab"><i
                            class="icon-plus2"></i> Create New Fees</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade" id="all-fees">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="ajax-store" method="post" action="{{ route('academic-period-classes.store') }}">
                                @csrf
                                <!-- Use loops for dropdowns -->
                                <input type="hidden" name="academic_period_id" value="{{ $academic->id }}">

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Courses <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="course_id" class="form-control select-search" required>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}
                                                    - {{ $course->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Instructors <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="instructor_id" class="form-control select-search" required>
                                            @foreach ($instructors as $instructor)
                                                <option
                                                    value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="new-period">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="ajax-store" method="post"
                                  action="{{ route('academic-period-management.store') }}">
                                @csrf
                                <!-- Add form fields for creating a new academic period -->
                                <div class="form-group row">
                                    <label for="study-mode" class="col-lg-3 col-form-label font-weight-semibold">Allowed
                                        Study Mode <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select type"
                                                class="form-control select-search" name="study_mode_id" id="study-mode">
                                            <option value=""></option>
                                            @foreach ($studyModes as $mode)
                                                <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="academic_period_id" value="{{ $academic->id }}">

                                <div class="form-group row">
                                    <label for="intake" class="col-lg-3 col-form-label font-weight-semibold">Intake
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select type"
                                                class="form-control select-search" name="academic_period_intake_id"
                                                id="intake">
                                            <option value=""></option>
                                            @foreach ($intakes as $i)
                                                <option value="{{ $i->id }}">{{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Result Threshold <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="view_results_threshold" maxlength="3" max="100"
                                               value="{{ old('view_results_threshold') }}" required type="number"
                                               class="form-control" placeholder="View results Threshold">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Registration Threshold
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="registration_threshold" maxlength="3" max="100"
                                               value="{{ old('registration_threshold') }}" required type="number"
                                               class="form-control" placeholder="Registration threshold">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Exam Slip Threshold
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="exam_slip_threshold" maxlength="3" max="100"
                                               value="{{ old('exam_slip_threshold') }}" required type="number"
                                               class="form-control" placeholder="Code">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Registration Threshold
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="registration_threshold" value="{{ old('registration_threshold') }}"
                                               required type="text" class="form-control" placeholder="Code">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Registration Start Date
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="registration_date" value="{{ old('registration_date') }}" required
                                               type="text" class="form-control date-pick" placeholder="AC start Date">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration Start
                                        Date <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="late_registration_date" value="{{ old('late_registration_date') }}"
                                               required type="text" class="form-control date-pick"
                                               placeholder="Late registration">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration End
                                        Date <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="late_registration_end_date"
                                               value="{{ old('late_registration_end_date') }}" required type="text"
                                               class="form-control date-pick" placeholder="Late end registration">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="new-ac-fees">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="ajax-store" method="post" action="{{ route('academic-period-fees.store') }}">
                                @csrf
                                <!-- Add form fields for creating a new academic period -->
                                <div class="form-group row">
                                    <label for="fees-id" class="col-lg-3 col-form-label font-weight-semibold">Fee Name
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select type"
                                                class="form-control select-search" name="fee_id" id="fees-id">
                                            <option value=""></option>
                                            @foreach ($fees as $fee)
                                                <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="academic_period_id" value="{{ $academic->id }}">


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Amount <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="amount" value="{{ old('view_results_threshold') }}" required
                                               type="number" class="form-control" placeholder="View results Threshold">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Academic Period List Ends --}}
@endsection
