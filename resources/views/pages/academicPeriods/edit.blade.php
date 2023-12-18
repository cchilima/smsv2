@extends('layouts.master')
@section('page_title', 'Edit Academic Period')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Academic Period</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('academic-periods.update', $academicPeriod->id) }}">
                        @csrf @method('PUT')

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Code <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="code" required type="text" class="form-control" placeholder="Code" value="{{ $academicPeriod->code }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Registration Date <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="registration_date" required type="date" class="form-control" placeholder="Registration Date" value="{{ $academicPeriod->registration_date }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration Date <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="late_registration_date" required type="date" class="form-control" placeholder="Late Registration Date" value="{{ $academicPeriod->late_registration_date }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Period Type <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="period_type_id" class="form-control" required>
                                    @foreach ($periodTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Intake <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="academic_period_intake_id" class="form-control" required>
                                    @foreach ($intakes as $intake)
                                        <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Study Mode <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="study_mode_id" class="form-control" required>
                                    @foreach ($studyModes as $mode)
                                        <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit <i class="icon-pencil ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Academic Period Ends --}}
@endsection
