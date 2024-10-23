@extends('layouts.master')
@section('page_title', 'Manage - ' . $academic->name)
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
                @can('academic_period.academic_information - create')
                    <li class="nav-item"><a href="#new-period" class="nav-link active show" data-toggle="tab"><i
                                class="icon-plus2"></i>
                            Add Information</a></li>
                @endcan
                @can('academic_period.classes - create')
                    <li class="nav-item"><a href="#all-fees" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                            Create Classes</a></li>
                @endcan
                @can('academic_period.fees - create')
                    <li class="nav-item"><a href="#new-ac-fees" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                            Create New Fees</a></li>
                @endcan
            </ul>

            <div class="tab-content">
                @can('academic_period.classes - create')
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
                                                <option value="">select option</option>
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
                                                <option value="">select option</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}">{{ $instructor->first_name }}
                                                        {{ $instructor->last_name }}</option>
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

                @endcan

                @can('academic_period.academic_information - create')
                    <div class="tab-pane fade active show" id="new-period">
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
                                            <select required data-placeholder="Select type" class="form-control select-search"
                                                name="study_mode_id" id="study-mode">
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
                                            <select required data-placeholder="Select type" class="form-control select-search"
                                                name="academic_period_intake_id" id="intake">
                                                <option value=""></option>
                                                @foreach ($intakes as $i)
                                                    <option value="{{ $i->id }}">{{ $i->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">View Results Threshold
                                            (%)<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="view_results_threshold" maxlength="3" max="100"
                                                value="{{ old('view_results_threshold') }}" required type="number"
                                                class="form-control" placeholder="View results threshold">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Download Exam Slip Threshold
                                            (%)
                                            <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="exam_slip_threshold" maxlength="3" max="100"
                                                value="{{ old('exam_slip_threshold') }}" required type="number"
                                                class="form-control" placeholder="Exam slip threshold">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Registration Threshold (%)
                                            <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="registration_threshold" value="{{ old('registration_threshold') }}"
                                                required type="text" class="form-control"
                                                placeholder="Registration threshold">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Registration Start Date
                                            <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="registration_date" value="{{ old('registration_date') }}" required
                                                type="text" class="form-control date-pick"
                                                placeholder="Registration start Date">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration Start
                                            Date <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="late_registration_date" value="{{ old('late_registration_date') }}"
                                                required type="text" class="form-control date-pick"
                                                placeholder="Late registration start date">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration End
                                            Date <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="late_registration_end_date"
                                                value="{{ old('late_registration_end_date') }}" required type="text"
                                                class="form-control date-pick" placeholder="Late registration end date">
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

                @endcan

                @can('academic_period.fees - create')
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
                                            <select required data-placeholder="Select type" class="form-control select-search"
                                                name="fee_id" id="fees-id">
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
                                                type="number" class="form-control" placeholder="Amount">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="program_id" class="col-lg-3 col-form-label font-weight-semibold">Program
                                            Name
                                            (<span class="text-warning">Optional</span>)</label>
                                        <div class="col-lg-9">
                                            <select data-placeholder="Select program" multiple
                                                class="form-control select-search multiselect" name="program_id[]"
                                                id="program_id">
                                                <option value=""></option>
                                                @foreach ($programsCourses as $p)
                                                    <option value="{{ $p->id }}">{{ $p->code . ' - ' . $p->name }}
                                                    </option>
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
                @endcan
            </div>
        </div>
    </div>

    {{-- Academic Period List Ends --}}
@endsection
