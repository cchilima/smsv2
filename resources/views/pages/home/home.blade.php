@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp


    @if(Auth::user()->userType->name == 'student')

    <div class="row">
        <div class="col-sm-6 col-xl-3">
    <div>
        <h3>Page Content goes here</h3>
    </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body">
                    <img style="width: 90%; height:90%" src="{{ 00 }}" alt="photo" class="rounded-circle">
                    <br>
                    <h3 class="mt-3">{{ $student->user->first_name.' '.$student->user->last_name }}</h3>
                </div>
            </div>
            <div class="justify-content-between">
                <button type="button" class="btn btn-primary">
                    Launch static backdrop modal
                </button>

                <button type="button" class="btn btn-primary">
                    Launch static backdrop modal
                </button>

            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Student General Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#account-info" class="nav-link active"
                               data-toggle="tab">Academic Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#next-kin" class="nav-link" data-toggle="tab">Sponsor Information</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="account-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Student ID</td>
                                    <td>{{ $student->id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Year of Study</td>
                                    <td class="academic-infor">
                                        <span>{{ $student->level->name }}</span>
                                        <select class="form-control" name="course_level_id"
                                                id="course_level_id-{{ $student->id }}" style="display: none;">
                                            <option selected
                                                    value="{{$student->level->id}}">{{$student->level->name}}</option>
                                            @foreach($course_levels as $level)
                                                <option value="{{$level->id}}">{{$level->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Registered Academic Year</td>
                                    <td>{{ '' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Study Category</td>
                                    <td class="academic-infor">
                                        <span>{{ $student->study_mode->name }}</span>
                                        <select class="form-control" name="study_mode_id"
                                                id="study_mode_id-{{ $student->id }}" style="display: none;">
                                            <option selected
                                                    value="{{$student->study_mode->id}}">{{ $student->study_mode->name }}</option>
                                            @foreach($studyModes as $mode)
                                                <option value="{{$mode->id}}">{{$mode->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Status</td>
                                    <td>{{ ($student->admission_status == 0 ? 'Active' : 'Inactive') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Name</td>
                                    <td class="academic-infor">
                                        <span>{{ $student->program->name }}</span>
                                        <select class="form-control" name="program_id"
                                                id="program_id-{{ $student->id }}" style="display: none;">
                                            <option selected
                                                    value="{{ $student->program->id }}">{{ $student->program->name }}</option>
                                            @foreach($programs as $program)
                                                <option value="{{$program->id}}">{{$program->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Code</td>
                                    <td>{{ $student->program->code }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Intake</td>
                                    <td class="academic-infor">
                                        <span>{{ $student->intake->name }}</span>
                                        <select class="form-control" name="academic_period_intake_id"
                                                id="academic_period_intake_id-{{ $student->id }}"
                                                style="display: none;">
                                            <option selected
                                                    value="{{$student->intake->id }}">{{ $student->intake->name  }}</option>
                                            @foreach($periodIntakes as $intake)
                                                <option value="{{$intake->id}}">{{$intake->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Period Type</td>
                                    <td class="academic-infor">
                                        <span>{{ $student->period_type->name }}</span>
                                        <select class="form-control" name="period_type_id"
                                                id="period_type_id-{{ $student->id }}" style="display: none;">
                                            <option selected
                                                    value="{{ $student->period_type->id }}">{{ $student->period_type->name }}</option>
                                            @foreach($periodTypes as $type)
                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="text-right mb-1 mt-4">
                                <button id="ajax-btn" type="button" onclick="manageAcademicInfor('{{$student->id}}')"
                                        class="btn btn-primary">Update Information <i
                                        class="icon-pencil ml-2"></i></button>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="next-kin">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Full Name</td>
                                    <td class="next-of-kin-infor">
                                        <span>{{ $student->user->userNextOfKin->full_name }}</span>
                                        <input value="{{ $student->user->userNextOfKin->full_name}}"
                                               id="name{{ $student->user->userNextOfKin->id }}" type="text"
                                               name="kin_full_name" class="form-control d-none">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td class="next-of-kin-infor">
                                        <span>{{ $student->user->userNextOfKin->mobile }}</span>
                                        <input value="{{ $student->user->userNextOfKin->mobile }}"
                                               id="mobile{{$student->user->userNextOfKin->id}}" type="text"
                                               name="kin_mobile" class="form-control d-none" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Telephone</td>
                                    <td class="next-of-kin-infor">
                                        <span>{{ $student->user->userNextOfKin->telephone }}</span>
                                        <input value="{{ $student->user->userNextOfKin->telephone }}"
                                               id="telephone{{$student->user->userNextOfKin->id}}" type="text"
                                               name="kin_telephone" class="d-none form-control">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">relation</td>
                                    <td class="next-of-kin-infor">
                                        <span>{{ $student->user->userNextOfKin->relationship->relationship }}</span>

                                        <select class="form-control d-none" name="kin_relationship_id"
                                                id="kin_relationship_id{{ $student->user->userNextOfKin->id }}">
                                            <option selected
                                                    value="{{ $student->user->userNextOfKin->relationship->id }}">{{ $student->user->userNextOfKin->relationship->relationship }}</option>
                                            @foreach($relationships as $relationship)
                                                <option
                                                    value="{{$relationship->id}}">{{$relationship->relationship}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Town</td>
                                    <td class="next-of-kin-infor">
                                        <span>{{ $student->user->userNextOfKin->town->name }}</span>
                                        <select class="form-control d-none" name="kin_town_id" id="kin_town_id{{ $student->user->userNextOfKin->id }}">
                                            <option
                                                value="{{ $student->user->userNextOfKin->town->id }}">{{ $student->user->userNextOfKin->town->name }}</option>
                                            @foreach($towns as $town)
                                                <option value="{{$town->id}}">{{$town->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td class="next-of-kin-infor">
                                        <span>{{ $student->user->userNextOfKin->province->name }}</span>
                                        <select class="form-control d-none" name="kin_province_id"
                                                id="kin_province_id{{ $student->user->userNextOfKin->id }}">
                                            <option
                                                value="{{ $student->user->userNextOfKin->province->id }}">{{ $student->user->userNextOfKin->province->name }}</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->id}}">{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Country</td>
                                    <td class="next-of-kin-infor">
                                        <span>{{ $student->user->userNextOfKin->country->country }}</span>
                                        <select class="form-control d-none" name="kin_country_id"
                                                id="kin_country_id{{ $student->user->userNextOfKin->id }}">
                                            <option
                                                value="{{ $student->user->userNextOfKin->country->id }}">{{ $student->user->userNextOfKin->country->country }}</option>
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}">{{$country->country}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="text-right mb-1 mt-4">
                                <button id="ajax-btn" type="button"
                                        onclick="UpdateNkininformation('{{ $student->user->userNextOfKin->id }}')"
                                        class="btn btn-primary">Update Information <i
                                        class="icon-pencil ml-2"></i></button>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="profile-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">First Name</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->first_name }}</span>
                                        <input value="{{ $student->user->first_name }}" id="fname{{ $student->user->id }}" required type="text"
                                               name="first_name" placeholder="First Name" class="form-control d-none">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Middle Name</td>
                                    <td class="personal-infor"><span>{{ $student->user->middle_name }}</span>
                                        <input value="{{ $student->user->middle_name }}" type="text" id="mname{{ $student->user->id }}" name="middle_name"
                                               placeholder="Middle Name" class="form-control d-none">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Last Names</td>
                                    <td class="personal-infor"><span>{{ $student->user->last_name }}</span>
                                        <input value="{{ $student->user->last_name }}" required type="text" id="lname{{ $student->user->id }}"
                                               name="last_name" placeholder="Last Name" class="form-control d-none">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td class="personal-infor"><span>{{ $student->user->gender }}</span>
                                        <select class="form-control d-none" required id="gender{{ $student->user->id }}" name="gender" data-placeholder="Choose..">
                                            <option value="$student->user->gender">{{ $student->user->gender }}</option>
                                            <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">
                                                Male
                                            </option>
                                            <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">
                                                Female
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Email</td>
                                    <td class="personal-infor"><span>{{ $student->user->email }}</span>
                                        <input value="{{ $student->user->email }}" required class="form-control d-none"
                                               placeholder="Email Address" id="email{{ $student->user->id }}" name="email" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">NRC</td>
                                    <td class="personal-infor"><span>{{ $student->user->userPersonalInfo->nrc }}</span>
                                        <input type="text" value="{{ $student->user->userPersonalInfo->nrc  }}" required
                                               name="nrc" class="form-control d-none" id="nrc{{ $student->user->id }}"
                                               placeholder="NRC Number xxxxxx/xx/x">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Passport Number</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo->passport }}</span>
                                        <input type="text" value="{{ $student->user->userPersonalInfo->passport }}"
                                               name="passport" class="form-control d-none" id="passport{{ $student->user->id }}"
                                               placeholder="Passport Number">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Date of Birth</td>
                                    <td class="personal-infor">
                                        <span>{{ date('j F Y',strtotime($student->user->userPersonalInfo->date_of_birth)) }}</span>
                                        <input name="date_of_birth"
                                               value="{{ $student->user->userPersonalInfo->date_of_birth }}" required
                                               type="text" class="form-control date-pick d-none" id="dob{{ $student->user->id }}"
                                               placeholder="Select Date...">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Marital Status</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo->userMaritalStatus->status }}</span>
                                        <select class="form-control d-none" required id="marital_status{{ $student->user->id }}"
                                                name="marital_status_id" data-placeholder="Choose..">
                                            <option
                                                value="{{ $student->user->userPersonalInfo->userMaritalStatus->id  }}">{{ $student->user->userPersonalInfo->userMaritalStatus->status  }}</option>
                                            @foreach($maritalStatuses as $maritalStatus)
                                                <option
                                                    value="{{$maritalStatus->id}}">{{$maritalStatus->status}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo->mobile }}</span>
                                        <input value="{{ $student->user->userPersonalInfo->mobile }}" required id="mobile{{ $student->user->id }}"
                                               type="text" name="mobile" class="form-control d-none" placeholder="">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Street</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo->street_main }}</span>
                                        <input value="{{ $student->user->userPersonalInfo->street_main }}" required
                                               type="text" name="street_main" class="form-control d-none" id="street{{ $student->user->id }}"
                                               placeholder="">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Town</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo->town->name }}</span>
                                        <select data-placeholder="Select Town" required class="form-control d-none"
                                                name="town_id" id="town{{ $student->user->id }}">
                                            <option
                                                value="{{ $student->user->userPersonalInfo->town->id }}">{{ $student->user->userPersonalInfo->town->name }}</option>
                                            @foreach($towns as $town)
                                                <option value="{{$town->id}}">{{$town->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo->province->name }}</span>
                                        <select data-placeholder="Select Province" required class="d-none form-control"
                                                name="province_id" id="province_id{{ $student->user->id }}">
                                            <option
                                                value="{{$student->user->userPersonalInfo->province->id }}">{{ $student->user->userPersonalInfo->province->name  }}</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->id}}">{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Country</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo->country->country }}</span>
                                        <select data-placeholder="Select Country" required class="d-none form-control"
                                                name="country_id" id="country_id{{ $student->user->id }}">
                                            <option selected
                                                    value="{{$student->user->userPersonalInfo->country->id }}"> {{$student->user->userPersonalInfo->country->country }}</option>
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}">{{$country->country}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="text-right mb-1 mt-4">
                                <button id="ajax-btn" type="button" onclick="prepareUserinfor('{{ $student->user->id }}')" class="btn btn-primary">Update Information <i
                                        class="icon-pencil ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Enrollments Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#account-info" class="nav-link active"
                               data-toggle="tab">{{ 'Account Details' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="account-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Student ID</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Year of Study</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Academic Year</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Study Category</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Status</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Name</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Code</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Intake</td>
                                    {{--                                <td>{{ $data['intake'] }}</td>--}}
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show" id="profile-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Email</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">NRC</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Date of Birth</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Marital Status</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Street</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Financial Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#account-info" class="nav-link active"
                               data-toggle="tab">{{ 'Account Details' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="account-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Student ID</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Year of Study</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Academic Year</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Study Category</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Status</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Name</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Code</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Intake</td>
                                    {{--                                <td>{{ $data['intake'] }}</td>--}}
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show" id="profile-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Email</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">NRC</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Date of Birth</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Marital Status</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Street</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Results Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#account-info" class="nav-link active"
                               data-toggle="tab">{{ 'Account Details' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="account-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Student ID</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Year of Study</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Academic Year</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Study Category</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Status</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Name</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Code</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Intake</td>
                                    {{--                                <td>{{ $data['intake'] }}</td>--}}
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show" id="profile-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Email</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">NRC</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Date of Birth</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Marital Status</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Street</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Account Management</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#account-info" class="nav-link active"
                               data-toggle="tab">{{ 'Change password' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="account-info">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="ajax-update" data-reload="#page-header" method="post"
                                          action="{{ route('users.store') }}">
                                        @csrf @method('POST')

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Password: <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <input value="{{ old('password') }}" required class="form-control"
                                                       placeholder="Password" name="password" type="password">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Confirm
                                                Password: <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <input value="{{ old('password_confirmation') }}" required
                                                       class="form-control" placeholder="Confirm Password"
                                                       name="password_confirmation" type="password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Force Change
                                                Password<span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <input required
                                                       class="form-control" placeholder="Confirm Password"
                                                       name="password_confirmation" type="checkbox">
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary">Submit form <i
                                                    class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade show" id="profile-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Email</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">NRC</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Date of Birth</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Marital Status</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Street</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endif


@endsection
