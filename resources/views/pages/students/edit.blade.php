@extends('layouts.master')
@section('page_title', 'Edit Student')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Student</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('students.update', $user->id) }}">
                        @csrf @method('PUT')

                        <h6>Personal data</h6>

                        <fieldset>
                        <legend>Personal Information</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>First Name: <span class="text-danger">*</span></label>
                                        <input value="{{ $user->first_name }}" required type="text" name="first_name" placeholder="First Name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Middle Name: </label>
                                        <input value="{{ $user->middle_name }}" type="text" name="middle_name" placeholder="Middle Name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Last Name: <span class="text-danger">*</span></label>
                                        <input value="{{ $user->last_name }}" required type="text" name="last_name" placeholder="Last Name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email Address: <span class="text-danger">*</span></label>
                                        <input value="{{ $user->email }}" required class="form-control" placeholder="Email Address" name="email" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date of Birth: <span class="text-danger">*</span></label>
                                        <input name="date_of_birth" value="{{ $personalInfo->date_of_birth }}" required type="text" class="form-control date-pick" placeholder="Select Date...">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="marital_status">Marital Status: <span class="text-danger">*</span></label>
                                    <select class="select form-control" required id="marital_status" name="marital_status_id" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach($maritalStatuses as $maritalStatus)
                                           <option value="{{$maritalStatus->id}}" {{ $personalInfo->marital_status_id === $maritalStatus->id ? 'selected' : '' }}>{{$maritalStatus->status}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender">Gender: <span class="text-danger">*</span></label>
                                        <select class="select form-control" required id="gender" name="gender" data-fouc data-placeholder="Choose..">
                                            <option value=""></option>
                                            <option value="Male" {{ $personalInfo->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $personalInfo->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Mobile: <span class="text-danger">*</span></label>
                                        <input value="{{ $personalInfo->mobile }}" required type="text" name="mobile" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Telephone:</label>
                                        <input value="{{ $personalInfo->telephone }}" type="text" name="telephone" class="form-control" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>NRC: <span class="text-danger">*</span></label>
                                        <input type="nrc" value="{{ $personalInfo->nrc }}" required name="nrc" class="form-control" placeholder="NRC Number xxxxxx/xx/x">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Passport Number: </label>
                                        <input type="text" value="{{ $personalInfo->passport }}" name="passport" class="form-control" placeholder="Passport Number">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Street / Road: <span class="text-danger">*</span></label>
                                        <input value="{{ $personalInfo->street_main }}" required type="text" name="street_main" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="lga_id">Town: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Town" required class="select-search form-control" name="town_id" id="lga_id">
                                        <option value=""></option>
                                        @foreach($towns as $town)
                                        <option value="{{$town->id}}" {{ $personalInfo->town_id === $town->id ? 'selected' : '' }}>{{$town->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="province_id">Province: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Province" required class="select-search form-control" name="province_id" id="province_id">
                                        <option value=""></option>
                                        @foreach($provinces as $province)
                                        <option value="{{$province->id}}" {{ $personalInfo->province_id === $province->id ? 'selected' : '' }}>{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="country_id">Country: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Country" required class="select-search form-control" name="country_id" id="country_id">
                                        <option value=""></option>
                                        @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $personalInfo->country_id === $country->id ? 'selected' : '' }}>
                                            {{ $country->country }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Upload Passport Photo:</label>
                                        <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                        <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
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
                                        <input value="{{ $nextOfKin->full_name }}" required type="text" name="kin_full_name" placeholder="Full Name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Phone: <span class="text-danger">*</span></label>
                                        <input value="{{ $nextOfKin->mobile }}" required type="text" name="kin_mobile" class="form-control" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Telephone:</label>
                                        <input value="{{ $nextOfKin->telephone }}" type="text" name="kin_telephone" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="kin_relationship_id">Relationship: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Relationship" required class="select-search form-control" name="kin_relationship_id" id="kin_relationship_id">
                                        <option value=""></option>
                                        @foreach($relationships as $relationship)
                                        <option value="{{$relationship->id}}" {{ $nextOfKin->relationship_id === $relationship->id ? 'selected' : '' }}>{{$relationship->relationship}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="kin_town_id">Town: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Town" required class="select-search form-control" name="kin_town_id" id="kin_town_id">
                                        <option value=""></option>
                                        @foreach($towns as $town)
                                        <option value="{{$town->id}}" {{ $nextOfKin->town_id === $town->id ? 'selected' : '' }}>{{$town->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="kin_province_id">Province: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Province" required class="select-search form-control" name="kin_province_id" id="kin_province_id">
                                        <option value=""></option>
                                        @foreach($provinces as $province)
                                        <option value="{{$province->id}}" {{ $nextOfKin->province_id === $province->id ? 'selected' : '' }}>{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="kin_country_id">Country: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Country" required class="select-search form-control" name="kin_country_id" id="kin_country_id">
                                        <option value=""></option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->id}}" {{ $nextOfKin->country_id === $country->id ? 'selected' : '' }}>{{$country->country}}</option>
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
                                    <select data-placeholder="Select Program" required class="select-search form-control" name="program_id" id="program_id">
                                        <option value=""></option>
                                        @foreach($programs as $program)
                                        <option value="{{$program->id}}" {{ $student->program_id === $program->id ? 'selected' : '' }}>{{$program->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="academic_period_intake_id">Academic Period Intake: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Academic Period Intake" required class="select-search form-control" name="academic_period_intake_id" id="academic_period_intake_id">
                                        <option value=""></option>
                                        @foreach($periodIntakes as $intake)
                                        <option value="{{$intake->id}}" {{ $student->academic_period_intake_id === $intake->id ? 'selected' : '' }}>{{$intake->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="study_mode_id">Study Mode: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Study Mode" required class="select-search form-control" name="study_mode_id" id="study_mode_id">
                                        <option value=""></option>
                                        @foreach($studyModes as $mode)
                                        <option value="{{$mode->id}}" {{ $student->study_mode_id === $mode->id ? 'selected' : '' }}>{{$mode->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="course_level_id">Course Level: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Course Level" required class="select-search form-control" name="course_level_id" id="course_level_id">
                                        <option value=""></option>
                                        @foreach($courseLevels as $level)
                                        <option value="{{$level->id}}"{{ $student->course_level_id === $level->id ? 'selected' : '' }} >{{$level->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="period_type_id">Period Type: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Period Type" required class="select-search form-control" name="period_type_id" id="period_type_id">
                                        <option value=""></option>
                                        @foreach($periodTypes as $type)
                                        <option value="{{$type->id}}" {{ $student->period_type_id === $type->id ? 'selected' : '' }}>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Admission Year: <span class="text-danger">*</span></label>
                                        <input value="{{$student->admission_year}}" required type="year" name="admission_year" class="form-control" placeholder="Admission Year">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Graduated: <span class="text-danger">*</span></label>
                                        <select class="select form-control" required name="graduated" data-fouc data-placeholder="Select Graduation Status">
                                            <option value=""></option>
                                            <option  value="1" {{ $student->graduated === 1 ? 'selected' : '' }}>Yes</option>
                                            <option  value="0" {{ $student->graduated === 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                                            

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update form <i class="icon-pencil ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Student Ends --}}
@endsection
