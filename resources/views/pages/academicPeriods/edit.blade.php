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
                        <!-- Add form fields for creating a new academic period -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $academicPeriod->name }}" required type="text" class="form-control" placeholder="Ac name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Code <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="code" value="{{ $academicPeriod->code }}" required type="text" class="form-control" placeholder="Code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Start Date <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="ac_start_date" value="{{ $academicPeriod->ac_start_date }}" required type="text" class="form-control date-pick" placeholder="AC start Date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">End Date <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="ac_end_date" value="{{ $academicPeriod->ac_end_date }}" required type="text" class="form-control date-pick" placeholder="AC end date">
                            </div>
                        </div>

                        <!-- Use loops for dropdowns -->
                        <div class="form-group row">
                            <label for="period-type" class="col-lg-3 col-form-label font-weight-semibold">Period Type <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select type" class="form-control select-search" name="period_type_id" id="period-type">
                                    <option selected value="{{ $academicPeriod->period_types->id }}">{{ $academicPeriod->period_types->name }}</option>
                                    @foreach ($periodTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
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
