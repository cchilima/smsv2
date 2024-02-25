@extends('layouts.master')
@section('page_title', 'Application - Step 2')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp




<div class="card">

<div class="card-header bg-white header-elements-inline">
    <h6 class="card-title"></h6>

    {!! Qs::getPanelOptions() !!}
</div>

<form id="ajax-reg" method="post" enctype="multipart/form-data"
    class="wizard-form steps-validation" action="{{ route('students.store') }}" data-fouc>
    @csrf

    <h6>Personal data</h6>

    <fieldset>
        <legend>Personal Information</legend>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>First Name: <span class="text-danger">*</span></label>
                    <input value="{{ old('first_name') }}" required type="text" name="first_name"
                        placeholder="First Name" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Middle Name: </label>
                    <input value="{{ old('middle_name') }}" type="text" name="middle_name"
                        placeholder="Middle Name" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Last Name: <span class="text-danger">*</span></label>
                    <input value="{{ old('last_name') }}" required type="text" name="last_name"
                        placeholder="Last Name" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Email Address: <span class="text-danger">*</span></label>
                    <input value="{{ old('email') }}" required class="form-control"
                        placeholder="Email Address" name="email" type="text">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Date of Birth: <span class="text-danger">*</span></label>
                    <input name="date_of_birth" value="{{ old('date_of_birth') }}" required
                        type="text" class="form-control date-pick" placeholder="Select Date...">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="gender">Gender: <span class="text-danger">*</span></label>
                    <select class="select form-control" required id="gender" name="gender"
                        data-fouc data-placeholder="Choose..">
                        <option value=""></option>
                        <option {{ old('gender') == 'Male' ? 'selected' : '' }} value="Male">
                            Male</option>
                        <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">
                            Female</option>
                    </select>
                </div>
            </div>


            <div class="col-md-3">
                <label for="marital_status">Marital Status: <span
                        class="text-danger">*</span></label>
                <select class="select form-control" required id="marital_status"
                    name="marital_status_id" data-fouc data-placeholder="Choose..">
                    <option value=""></option>
                    @foreach ($maritalStatuses as $maritalStatus)
                        <option value="{{ $maritalStatus->id }}">{{ $maritalStatus->status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Mobile: <span class="text-danger">*</span></label>
                    <input value="{{ old('mobile') }}" required type="text" name="mobile"
                        class="form-control" placeholder="">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="country_id">Country: <span class="text-danger">*</span></label>
                    <select data-placeholder="Select Country" required
                        class="select-search form-control" name="country_id" id="country_id">
                        <option disabled selected value=""></option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="province_id">Province: <span class="text-danger">*</span></label>
                    <select data-placeholder="Select Province" required
                        class="select-search form-control" name="province_id" id="province_id">
                        <option disabled selected></option>
                        {{-- @foreach ($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach --}}
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="town_id">Town: <span class="text-danger">*</span></label>
                    <select data-placeholder="Select Town" required
                        class="select-search form-control" name="town_id" id="town_id">
                        <option disabled selected></option>
                        {{-- @foreach ($towns as $town)
                            <option value="{{ $town->id }}">{{ $town->name }}</option>
                        @endforeach --}}
                    </select>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label>Street / Road: <span class="text-danger">*</span></label>
                    <input value="{{ old('street_main') }}" required type="text"
                        name="street_main" class="form-control" placeholder="">
                </div>
            </div>

        </div>




    </fieldset>



    <h6>Academics Information</h6>

    <fieldset>
        <legend>Academics Information</legend>
        <div class="row">

            <div class="col-md-3">
                <label for="program_id">Program: <span class="text-danger">*</span></label>
                <select data-placeholder="Select Program" required
                    class="select-search form-control" name="program_id" id="program_id">
                    <option value=""></option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="academic_period_intake_id">Academic Period Intake: <span
                        class="text-danger">*</span></label>
                <select data-placeholder="Select Academic Period Intake" required
                    class="select-search form-control" name="academic_period_intake_id"
                    id="academic_period_intake_id">
                    <option value=""></option>
                    @foreach ($periodIntakes as $intake)
                        <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="study_mode_id">Study Mode: <span class="text-danger">*</span></label>
                <select data-placeholder="Select Study Mode" required
                    class="select-search form-control" name="study_mode_id" id="study_mode_id">
                    <option value=""></option>
                    @foreach ($studyModes as $mode)
                        <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>




    </fieldset>


</form>
</div>



{{-- Script for handling dependent select inputs for location input --}}
    <script>
        const getProvinces = (countryId, provinceSelector, townSelector) => {
            $.ajax({
                url: `/countries/${countryId}/provinces`,
                type: "GET",
                dataType: "json",
                success: (data) => {
                    $(provinceSelector).empty();
                    $(townSelector).empty();
                    $(provinceSelector).append(`<option disabled selected></option>`)
                    $.each(data, (_, value) => {
                        $(provinceSelector).append(
                            `<option value="${value.id}">${value.name}</option>`);
                    });
                }
            });
        }

        const getTowns = (provinceId, townSelector) => {
            $.ajax({
                url: `/provinces/${provinceId}/towns`,
                type: "GET",
                dataType: "json",
                success: (data) => {
                    $(townSelector).empty();
                    $(townSelector).append(`<option disabled selected></option>`)
                    $.each(data, (_, value) => {
                        $(townSelector).append(
                            `<option value="${value.id}">${value.name}</option>`);
                    });
                }
            });
        }

        $(document).ready(() => {

            $('#country_id').change(function() {
                const countryId = $(this).val();

                if (countryId) {
                    getProvinces(countryId, '#province_id', '#town_id');
                } else {
                    $('#province_id').empty();
                }
            });

            $('#province_id').change(function() {
                const provinceId = $(this).val();

                if (provinceId) {
                    getTowns(provinceId, '#town_id');
                } else {
                    $('#town_id').empty();
                }
            });

        });
    </script>

@endsection
