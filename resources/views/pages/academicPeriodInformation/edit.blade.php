@extends('layouts.master')
@section('page_title', 'Edit Information for - ' . $periods->academic_period->name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit {{ $periods->academic_period->name }} Information</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="ajax-store" method="post"
                        action="{{ route('academic-period-management.update', $periods->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Add form fields for creating a new academic period -->
                        <div class="form-group row">
                            <label for="study-mode" class="col-lg-3 col-form-label font-weight-semibold">Allowed Study Mode
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select type" class="form-control select-search"
                                    name="study_mode_id" id="study-mode">
                                    <option selected value="{{ $periods->study_mode_id }}">{{ $periods->study_mode->name }}
                                    </option>
                                    @foreach ($studyModes as $mode)
                                        <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="intake" class="col-lg-3 col-form-label font-weight-semibold">Intake <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select type" class="form-control select-search"
                                    name="academic_period_intake_id" id="intake">
                                    <option selected value="{{ $periods->academic_period_intake_id }}">
                                        {{ $periods->intake->name }}</option>
                                    @foreach ($intakes as $i)
                                        <option value="{{ $i->id }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Result Threshold <span
                                    class="text-danger">* %</span></label>
                            <div class="col-lg-9">
                                <input name="view_results_threshold" maxlength="3" max="100"
                                    value="{{ $periods->view_results_threshold }}" required type="number"
                                    class="form-control" placeholder="View results Threshold">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Registration Threshold <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="registration_threshold" maxlength="3" max="100"
                                    value="{{ $periods->registration_threshold }}" required type="number"
                                    class="form-control" placeholder="Registration threshold">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Exam Slip Threshold <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="exam_slip_threshold" maxlength="3" max="100"
                                    value="{{ $periods->exam_slip_threshold }}" required type="number"
                                    class="form-control" placeholder="Code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Registration Threshold <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="registration_threshold" value="{{ $periods->registration_threshold }}"
                                    required type="text" class="form-control" placeholder="Code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Registration Start Date <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="registration_date" value="{{ $periods->registration_date }}" required
                                    type="text" class="form-control date-pick" placeholder="AC start Date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration Start Date <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="late_registration_date" value="{{ $periods->late_registration_date }}"
                                    required type="text" class="form-control date-pick" placeholder="Late registration">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration Start Date <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="late_registration_end_date" value="{{ $periods->late_registration_end_date }}"
                                    required type="text" class="form-control date-pick"
                                    placeholder="Late end registration">
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

    {{-- Edit Academic Period Ends --}}
@endsection
