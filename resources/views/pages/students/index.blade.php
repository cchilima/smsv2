@extends('layouts.master')
@section('page_title', 'Manage Students')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Students</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-students" class="nav-link active" data-toggle="tab">Manage Students</a></li>
                <li class="nav-item"><a href="#new-student" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New Student</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-students">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Student number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $student->user->first_name }} {{ $student->user->last_name }}</td>

                                    <td>{{ $student->id }}</td>

                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if (Qs::userIsTeamSA())
                                                        <a href="{{ route('students.edit', $student->id) }}"
                                                            class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    @if (Qs::userIsSuperAdmin())
                                                        <a id="{{ $student->id }}" onclick="confirmDelete(this.id)"
                                                            href="#" class="dropdown-item"><i class="icon-trash"></i>
                                                            Delete</a>
                                                        <form method="post" id="item-delete-{{ $student->id }}"
                                                            action="{{ route('students.destroy', $student->id) }}"
                                                            class="hidden">@csrf @method('delete')</form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="new-student">

                    <div class="card">

                        <div class="card-header bg-white header-elements-inline">
                            <h6 class="card-title">Please fill The form Below To Admit A New Student</h6>

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
                                            <label>Telephone:</label>
                                            <input value="{{ old('telephone') }}" type="text" name="telephone"
                                                class="form-control" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>NRC: <span class="text-danger">*</span></label>
                                            <input type="nrc" value="{{ old('nrc') }}" required name="nrc"
                                                class="form-control" placeholder="NRC Number xxxxxx/xx/x">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Passport Number: </label>
                                            <input type="text" value="{{ old('passport_number') }}" name="passport"
                                                class="form-control" placeholder="Passport Number">
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="d-block">Upload Passport Photo:</label>
                                            <input value="{{ old('photo') }}" accept="image/*" type="file"
                                                name="photo" class="form-input-styled" data-fouc>
                                            <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size
                                                2Mb</span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h6>Next of Kin Information</h6>

                            <fieldset>

                                <legend>Next of Kin Information</legend>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Full Name: <span class="text-danger">*</span></label>
                                            <input value="{{ old('kin_full_name') }}" required type="text"
                                                name="kin_full_name" placeholder="Full Name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone: <span class="text-danger">*</span></label>
                                            <input value="{{ old('kin_phone') }}" required type="text"
                                                name="kin_mobile" class="form-control" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Telephone:</label>
                                            <input value="{{ old('kin_tel') }}" type="text" name="kin_telephone"
                                                class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="kin_relationship_id">Relationship: <span
                                                class="text-danger">*</span></label>
                                        <select data-placeholder="Select Relationship" required
                                            class="select-search form-control" name="kin_relationship_id"
                                            id="kin_relationship_id">
                                            <option value=""></option>
                                            @foreach ($relationships as $relationship)
                                                <option value="{{ $relationship->id }}">{{ $relationship->relationship }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="kin_town_id">Town: <span class="text-danger">*</span></label>
                                        <select data-placeholder="Select Town" required class="select-search form-control"
                                            name="kin_town_id" id="kin_town_id">
                                            <option value=""></option>
                                            @foreach ($towns as $town)
                                                <option value="{{ $town->id }}">{{ $town->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="kin_province_id">Province: <span class="text-danger">*</span></label>
                                        <select data-placeholder="Select Province" required
                                            class="select-search form-control" name="kin_province_id"
                                            id="kin_province_id">
                                            <option value=""></option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="kin_country_id">Country: <span class="text-danger">*</span></label>
                                        <select data-placeholder="Select Country" required
                                            class="select-search form-control" name="kin_country_id" id="kin_country_id">
                                            <option value=""></option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <h6>Academics Information</h6>

                            <fieldset>
                                <legend>Academics Information</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="program_id">Program: <span class="text-danger">*</span></label>
                                        <select data-placeholder="Select Program" required
                                            class="select-search form-control" name="program_id" id="program_id">
                                            <option value=""></option>
                                            @foreach ($programs as $program)
                                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
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

                                    <div class="col-md-4">
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

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="course_level_id">Course Level: <span
                                                class="text-danger">*</span></label>
                                        <select data-placeholder="Select Course Level" required
                                            class="select-search form-control" name="course_level_id"
                                            id="course_level_id">
                                            <option value=""></option>
                                            @foreach ($courseLevels as $level)
                                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="period_type_id">Period Type: <span
                                                class="text-danger">*</span></label>
                                        <select data-placeholder="Select Period Type" required
                                            class="select-search form-control" name="period_type_id" id="period_type_id">
                                            <option value=""></option>
                                            @foreach ($periodTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Admission Year: <span class="text-danger">*</span></label>
                                            <input value="{{ old('admission_year') }}" required type="year"
                                                name="admission_year" class="form-control" placeholder="Admission Year">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Graduated: <span class="text-danger">*</span></label>
                                            <select class="select form-control" required name="graduated" data-fouc
                                                data-placeholder="Select Graduation Status">
                                                <option value=""></option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Student List Ends --}}

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
