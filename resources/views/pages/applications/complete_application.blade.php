@extends('layouts.master')
@section('page_title', 'Application - Step 2')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">

        <div class="card-header bg-white header-elements-inline">

            {!! Qs::getPanelOptions() !!}

        </div>

        @if ($application->status == 'incomplete')

            <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-update"
                action="{{ route('application.save_application', $application_id) }}" data-fouc>

                @csrf @method('PUT')

                <h6>Personal data</h6>

                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>First Name: </label>
                                <input value="{{ $application->first_name }}" type="text" name="first_name"
                                    placeholder="First Name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Middle Name: </label>
                                <input value="{{ $application->middle_name }}" type="text" name="middle_name"
                                    placeholder="Middle Name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Last Name: </label>
                                <input value="{{ $application->last_name }}" type="text" name="last_name"
                                    placeholder="Last Name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email Address: </label>
                                <input value="{{ $application->email }}" class="form-control" placeholder="Email Address"
                                    name="email" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date of Birth: </label>
                                <input name="date_of_birth" value="{{ $application->date_of_birth }}" type="text"
                                    class="form-control date-pick" placeholder="Select Date...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Gender: </label>
                                <select class="select form-control" id="gender" name="gender" data-fouc
                                    data-placeholder="Choose..">
                                    <option value=""></option>
                                    <option {{ $application->gender == 'Male' ? 'selected' : '' }} value="Male">
                                        Male</option>
                                    <option {{ $application->gender == 'Female' ? 'selected' : '' }} value="Female">
                                        Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mobile: </label>
                                <input value="{{ $application->phone_number }}" type="text" name="phone_number"
                                    class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Address: </label>
                                <input value="{{ $application->address }}" type="text" name="address"
                                    class="form-control" placeholder="">
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="country_id">Country: </label>
                                <select data-placeholder="Select Country" class="select-search form-control"
                                    name="country_id" id="country_id">
                                    <option disabled selected value=""></option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ $application->country_id === $country->id ? 'selected' : '' }}>
                                            {{ $country->country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="province_id">Province: </label>
                                <select data-placeholder="Select Province" class="select-search form-control"
                                    name="province_id" id="province_id">
                                    <option disabled selected></option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ $application->province_id === $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="town_id">Town: </label>
                                <select data-placeholder="Select Town" class="select-search form-control" name="town_id"
                                    id="town_id">
                                    <option disabled selected></option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}"
                                            {{ $application->town_id === $town->id ? 'selected' : '' }}>
                                            {{ $town->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                </fieldset>

                <h6>Academics Information</h6>

                <fieldset>
                    <legend>Academics Information</legend>
                    <div class="row">

                        <div class="col-md-3">
                            <label for="program_id">Program: </label>
                            <select data-placeholder="Select Program" class="select-search form-control" name="program_id"
                                id="program_id">
                                <option value=""></option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}"
                                        {{ $application->program_id === $program->id ? 'selected' : '' }}>
                                        {{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="academic_period_intake_id">Academic Period Intake: </label>
                            <select data-placeholder="Select Academic Period Intake" class="select-search form-control"
                                name="academic_period_intake_id" id="academic_period_intake_id">
                                <option value=""></option>
                                @foreach ($periodIntakes as $intake)
                                    <option value="{{ $intake->id }}"
                                        {{ $application->academic_period_intake_id === $intake->id ? 'selected' : '' }}>
                                        {{ $intake->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="study_mode_id">Study Mode: </label>
                            <select data-placeholder="Select Study Mode" class="select-search form-control"
                                name="study_mode_id" id="study_mode_id">
                                <option value=""></option>
                                @foreach ($studyModes as $mode)
                                    <option value="{{ $mode->id }}"
                                        {{ $application->study_mode_id === $mode->id ? 'selected' : '' }}>
                                        {{ $mode->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </fieldset>

                <h6>Attachments</h6>

                <fieldset>

                    <legend>Single Attachment - NRC, Certificate, Statement, </legend>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Upload File: </label>
                                <input value="{{ old('attachment') }}" accept="pdf" type="file" name="attachment"
                                    class="form-input-styled" data-fouc>
                                <span class="form-text text-muted">Accepted Files: PDF (Max 5MB)</span>
                            </div>
                        </div>
                    </div>

                </fieldset>

            </form>
        @else
            <br><br><br>
            <div class="container">
                <h5>Personal data</h5>
                <p>First Name : {{ $application->first_name ?? 'Missing' }}</p>
                <p>Middle Name : {{ $application->middle_name ?? 'Missing' }}</p>
                <p>Last Name : {{ $application->last_name ?? 'Missing' }}</p>
                <p>Gender : {{ $application->gender ?? 'Missing' }}</p>
                <p>Date of Birth : {{ $application->date_of_birth ?? 'Missing' }}</p>

                <br><br>

                <h5>Contacts</h5>
                <p>Email Address: {{ $application->email ?? 'Missing' }}</p>
                <p>Mobile: {{ $application->phone_number ?? 'Missing' }}</p>

                <br><br>

                <h5>Residency</h5>
                <p>Country : {{ $application->country->country ?? 'Missing' }}</p>
                <p>Province : {{ $application->province->name ?? 'Missing' }}</p>
                <p>Town : {{ $application->town->name ?? 'Missing' }}</p>
                <p>Address : {{ $application->address ?? 'Missing' }}</p>

                <br><br>

                <!-- Academics Information -->
                <h5>Academic Information</h5>
                <p>Program : {{ $application->program->name ?? 'Missing' }}</p>
                <p>Academic Period Intake : {{ $application->intake->name ?? 'Missing' }}</p>
                <p>Study Mode : {{ $application->study_mode->name ?? 'Missing' }}</p>

                <br><br>

                <!-- Attachments -->
                <h5>Attachment</h5>
                <button class="btn btn-primary">download</button>
                <!-- Display attachments if any -->
            </div>
            <br><br><br>
        @endif
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
                            `<option value="${value.id}" >${value.name}</option>`);
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
