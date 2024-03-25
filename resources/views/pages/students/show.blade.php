@extends('layouts.master')
@section('page_title', 'Student Profile - ' . $student->user->first_name . ' ' . $student->user->last_name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    @if (session('status'))
        <?php Qs::goWithSuccessCustom(session('status')); ?>
    @endif

    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body p-4">

                    @php
                        $passportPhotoUrl = !$student->user->userPersonalInfo->passport_photo_path
                            ? asset('images/default-avatar.png')
                            : asset($student->user->userPersonalInfo->passport_photo_path);
                    @endphp

                    <img style="aspect-ratio: 1/1" src="{{ $passportPhotoUrl }}" alt="photo"
                        class="rounded-circle object-fit-cover w-100 h-100">
                    <br>
                    <h3 class="mt-3">{{ $student->user->first_name . ' ' . $student->user->last_name }}</h3>
                </div>
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
                            <a href="#account-info" class="nav-link active" data-toggle="tab">Academic Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#next-kin" class="nav-link" data-toggle="tab">Sponsor Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#downloads-info" class="nav-link" data-toggle="tab">Student Downloads</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
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
                                                <option selected value="{{ $student->level->id }}">
                                                    {{ $student->level->name }}</option>
                                                @foreach ($course_levels as $level)
                                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
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
                                                <option selected value="{{ $student->study_mode->id }}">
                                                    {{ $student->study_mode->name }}</option>
                                                @foreach ($studyModes as $mode)
                                                    <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Status</td>
                                        <td>{{ $student->admission_status == 0 ? 'Active' : 'Inactive' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Programme Name</td>
                                        <td class="academic-infor">
                                            <span>{{ $student->program->name }}</span>
                                            <select class="form-control" name="program_id"
                                                id="program_id-{{ $student->id }}" style="display: none;">
                                                <option selected value="{{ $student->program->id }}">
                                                    {{ $student->program->name }}</option>
                                                @foreach ($programs as $program)
                                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
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
                                                id="academic_period_intake_id-{{ $student->id }}" style="display: none;">
                                                <option selected value="{{ $student->intake->id }}">
                                                    {{ $student->intake->name }}</option>
                                                @foreach ($periodIntakes as $intake)
                                                    <option value="{{ $intake->id }}">{{ $intake->name }}</option>
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
                                                <option selected value="{{ $student->period_type->id }}">
                                                    {{ $student->period_type->name }}</option>
                                                @foreach ($periodTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="text-right mb-1 mt-4">
                                <button id="ajax-btn" type="button" onclick="manageAcademicInfor('{{ $student->id }}')"
                                    class="btn btn-primary">Update Information <i class="icon-pencil ml-2"></i></button>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="next-kin">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Full Name</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->full_name }}</span>
                                            <input value="{{ $student->user->userNextOfKin->full_name }}"
                                                id="name{{ $student->user->userNextOfKin->id }}" type="text"
                                                name="kin_full_name" class="form-control d-none">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Mobile</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->mobile }}</span>
                                            <input value="{{ $student->user->userNextOfKin->mobile }}"
                                                id="mobile{{ $student->user->userNextOfKin->id }}" type="text"
                                                name="kin_mobile" class="form-control d-none" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Telephone</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->telephone }}</span>
                                            <input value="{{ $student->user->userNextOfKin->telephone }}"
                                                id="telephone{{ $student->user->userNextOfKin->id }}" type="text"
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
                                                    value="{{ $student->user->userNextOfKin->relationship->id }}">
                                                    {{ $student->user->userNextOfKin->relationship->relationship }}
                                                </option>
                                                @foreach ($relationships as $relationship)
                                                    <option value="{{ $relationship->id }}">
                                                        {{ $relationship->relationship }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Town</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->town->name }}</span>
                                            <select class="form-control d-none" name="kin_town_id"
                                                id="kin_town_id{{ $student->user->userNextOfKin->id }}">
                                                <option value="{{ $student->user->userNextOfKin->town->id }}">
                                                    {{ $student->user->userNextOfKin->town->name }}</option>
                                                @foreach ($towns as $town)
                                                    <option value="{{ $town->id }}">{{ $town->name }}</option>
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
                                                <option value="{{ $student->user->userNextOfKin->province->id }}">
                                                    {{ $student->user->userNextOfKin->province->name }}</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
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
                                                <option value="{{ $student->user->userNextOfKin->country->id }}">
                                                    {{ $student->user->userNextOfKin->country->country }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->country }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-right mb-1 mt-4">
                                <button id="ajax-btn" type="button"
                                    onclick="UpdateNkininformation('{{ $student->user->userNextOfKin->id }}')"
                                    class="btn btn-primary">Update Information <i class="icon-pencil ml-2"></i></button>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="profile-info">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">First Name</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->first_name }}</span>
                                            <input value="{{ $student->user->first_name }}"
                                                id="fname{{ $student->user->id }}" required type="text"
                                                name="first_name" placeholder="First Name" class="form-control d-none">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Middle Name</td>
                                        <td class="personal-infor"><span>{{ $student->user->middle_name }}</span>
                                            <input value="{{ $student->user->middle_name }}" type="text"
                                                id="mname{{ $student->user->id }}" name="middle_name"
                                                placeholder="Middle Name" class="form-control d-none">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Last Names</td>
                                        <td class="personal-infor"><span>{{ $student->user->last_name }}</span>
                                            <input value="{{ $student->user->last_name }}" required type="text"
                                                id="lname{{ $student->user->id }}" name="last_name"
                                                placeholder="Last Name" class="form-control d-none">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Gender</td>
                                        <td class="personal-infor"><span>{{ $student->user->gender }}</span>
                                            <select class="form-control d-none" required
                                                id="gender{{ $student->user->id }}" name="gender"
                                                data-placeholder="Choose..">
                                                <option value="$student->user->gender">{{ $student->user->gender }}
                                                </option>
                                                <option {{ old('gender') == 'Male' ? 'selected' : '' }} value="Male">
                                                    Male
                                                </option>
                                                <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">
                                                    Female
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Email</td>
                                        <td class="personal-infor"><span>{{ $student->user->email }}</span>
                                            <input value="{{ $student->user->email }}" required
                                                class="form-control d-none" placeholder="Email Address"
                                                id="email{{ $student->user->id }}" name="email" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">NRC</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo->nrc }}</span>
                                            <input type="text" value="{{ $student->user->userPersonalInfo->nrc }}"
                                                required name="nrc" class="form-control d-none"
                                                id="nrc{{ $student->user->id }}" placeholder="NRC Number xxxxxx/xx/x">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Passport Number</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo->passport }}</span>
                                            <input type="text"
                                                value="{{ $student->user->userPersonalInfo->passport }}" name="passport"
                                                class="form-control d-none" id="passport{{ $student->user->id }}"
                                                placeholder="Passport Number">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Date of Birth</td>
                                        <td class="personal-infor">
                                            <span>{{ date('j F Y', strtotime($student->user->userPersonalInfo->date_of_birth)) }}</span>
                                            <input name="date_of_birth"
                                                value="{{ $student->user->userPersonalInfo->date_of_birth }}" required
                                                type="text" class="form-control date-pick d-none"
                                                id="dob{{ $student->user->id }}" placeholder="Select Date...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Marital Status</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo->userMaritalStatus->status }}</span>
                                            <select class="form-control d-none" required
                                                id="marital_status{{ $student->user->id }}" name="marital_status_id"
                                                data-placeholder="Choose..">
                                                <option
                                                    value="{{ $student->user->userPersonalInfo->userMaritalStatus->id }}">
                                                    {{ $student->user->userPersonalInfo->userMaritalStatus->status }}
                                                </option>
                                                @foreach ($maritalStatuses as $maritalStatus)
                                                    <option value="{{ $maritalStatus->id }}">
                                                        {{ $maritalStatus->status }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Mobile</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo->mobile }}</span>
                                            <input value="{{ $student->user->userPersonalInfo->mobile }}" required
                                                id="mobile{{ $student->user->id }}" type="text" name="mobile"
                                                class="form-control d-none" placeholder="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Street</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo->street_main }}</span>
                                            <input value="{{ $student->user->userPersonalInfo->street_main }}" required
                                                type="text" name="street_main" class="form-control d-none"
                                                id="street{{ $student->user->id }}" placeholder="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Town</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo->town->name }}</span>
                                            <select data-placeholder="Select Town" required class="form-control d-none"
                                                name="town_id" id="town{{ $student->user->id }}">
                                                <option value="{{ $student->user->userPersonalInfo->town->id }}">
                                                    {{ $student->user->userPersonalInfo->town->name }}</option>
                                                @foreach ($towns as $town)
                                                    <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Province</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo->province->name }}</span>
                                            <select data-placeholder="Select Province" required
                                                class="d-none form-control" name="province_id"
                                                id="province_id{{ $student->user->id }}">
                                                <option value="{{ $student->user->userPersonalInfo->province->id }}">
                                                    {{ $student->user->userPersonalInfo->province->name }}</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
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
                                                    value="{{ $student->user->userPersonalInfo->country->id }}">
                                                    {{ $student->user->userPersonalInfo->country->country }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->country }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="text-right mb-1 mt-4">
                                <button id="ajax-btn" type="button"
                                    onclick="prepareUserinfor('{{ $student->user->id }}')"
                                    class="btn btn-primary">Update Information <i class="icon-pencil ml-2"></i></button>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="downloads-info">
{{--                            <form class="ajax-store" method="post" action="{{ route('student.id.download') }}">--}}
{{--                                @csrf--}}
{{--                                <input name="student_id" hidden value="{{ $student->id }}" type="text">--}}
{{--                                <div class="text-left">--}}
{{--                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Download Student ID<i--}}
{{--                                            class="icon-paperplane ml-2"></i></button>--}}
{{--                                </div>--}}
{{--                            </form>--}}
                            <a href="{{ route('student.id.download',$student->id) }}" class="btn btn-primary" type="button"
                               >Download ID</a>
                            <a href="{{ route('student.transcript.download',$student->id) }}" class="btn btn-primary" type="button"
                            >Download Transcript</a>
                            <a href="{{ route('student.exam.slip.download',$student->id) }}" class="btn btn-primary" type="button"
                            >Download Exam slip</a>
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
                        @foreach ($enrollments as $innerIndex => $academicData)
                            <li class="nav-item">
                                <a href="#account-{{ $academicData['academic_period_id'] }}" class="nav-link"
                                    data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        @foreach ($enrollments as $innerIndex => $academicData)
                            <div class="tab-pane fade show" id="account-{{ $academicData['academic_period_id'] }}">

                                <table class="table table-hover table-striped-columns mb-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <h5 class="p-2"> Code :
                                                <strong>{{ $academicData['academic_period_code'] }}</strong>
                                            </h5>
                                            <h5 class="p-2">Name :
                                                <strong>{{ $academicData['academic_period_name'] }}</strong>
                                            </h5>
                                        </div>
                                        <div>
                                            <form action="{{ route('registration.summary') }}" method="get">
                                                @csrf
                                                <input name="academic_period_id" type="hidden"
                                                    value="{{ $academicData['academic_period_id'] }}" />
                                                <input name="student_number" type="hidden"
                                                    value="{{ $student->id }}" />
                                                <button type="submit" class="btn btn-primary mt-2">Download
                                                    summary</button>
                                            </form>

                                        </div>

                                    </div>
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Course Code</th>
                                            <th>Course Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($academicData['courses'] as $course)
                                            <tr>
                                                <th>{{ $loop->iteration }}</th>
                                                <td>{{ $course['course_code'] }}</td>
                                                <td>{{ $course['course_title'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        @endforeach

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
                            <a href="#invoice" class="nav-link"
                                data-toggle="tab">{{ 'Invoice Student - Academic period' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#invoice-custom" class="nav-link" data-toggle="tab">{{ 'Invoice Student' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#invoices" class="nav-link" data-toggle="tab">{{ 'Invoices' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#statements" class="nav-link" data-toggle="tab">{{ 'Statements' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#payment-history" class="nav-link" data-toggle="tab">{{ 'Payment History' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#collect-payment" class="nav-link" data-toggle="tab">{{ 'Collect Payment' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane fade show" id="invoice-custom">
                            <form class="ajax-store" method="post" action="{{ route('invoices.custom-invoice') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fee">Fees: <span class="text-danger">*</span></label>
                                            <select data-placeholder="Select Fee" required
                                                class="select-search form-control" name="fee_id" id="fee">
                                                <option value=""></option>
                                                @foreach ($fees as $fee)
                                                    <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="invoice-amount">Enter Amount</label>
                                            <input type="number" class="form-control" id="invoice-amount"
                                                name="amount" placeholder="ZMW" required>
                                        </div>
                                    </div>
                                </div>

                                <input name="student_id" type="hidden" value="{{ $student->id }}">

                                <div class="form-group text-left">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Invoice Student <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade show" id="invoice">
                            @if (!$isInvoiced)
                                <form class="ajax-store" method="post" action="{{ route('invoices.invoice') }}">
                                    @csrf
                                    <input name="academic_period" hidden
                                        value="{{ $student->academic_info->academic_period_id }}" type="text">
                                    <input name="student_id" hidden value="{{ $student->id }}" type="text">
                                    <div class="text-left">
                                        <button id="ajax-btn" type="submit" class="btn btn-primary">invoice student<i
                                                class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            @else
                                <div class="container">
                                    <p>{{ $student->user->first_name . ' ' . $student->user->last_name }}, has already
                                        been
                                        invoice for this academic period.</p>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade show" id="collect-payment">

                            <form class="ajax-store" method="post" action="{{ route('statements.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="amount">Enter Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        placeholder="ZMW" required>
                                </div>
                                <div class="form-group">
                                    <label for="method">Method <span class="text-danger">*</span></label>
                                    <select data-placeholder="Payment method" required class="select-search form-control"
                                        name="payment_method_id" id="method">
                                        <option value=""></option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input hidden type="number" class="form-control" name="academic_period"
                                        value="{{ $student->academic_info->academic_period_id }}" required>
                                </div>

                                <div class="form-group">
                                    <input hidden type="text" class="form-control" name="student_id"
                                        value="{{ $student->id }}" required>
                                </div>

                                <div class="text-left">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>

                        </div>

                        <div class="tab-pane fade show" id="statements">
                            <div class="mb-2 d-flex justify-content-end">
                                <form action="{{ route('student.export-statements', $student->id) }}" method="get">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-download4 mr-1 lr-lg-2"></i>
                                        <span>Export Statements</span>
                                    </button>
                                </form>
                            </div>

                            @foreach ($student->invoices as $key => $invoice)
                                <table class="table table-bordered mb-3 mb-lg-4">
                                    <thead>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>

                                    </thead>
                                    <tbody>

                                        <tr>
                                            <h4 class="d-flex align-items-center justify-content-between">
                                                <span>INV - {{ ++$key }}</span>

                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <form
                                                            action="{{ route('student.download-statement', $invoice->id) }}"
                                                            method="get">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="icon-download4 lr-lg-2"></i>
                                                                <span>PDF</span>
                                                            </button>
                                                        </form>
                                                    </div>

                                                </div>

                                            </h4>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Opening Balance</b></td>
                                            <td>K {{ $invoice->details->sum('amount') }}</td>
                                        </tr>
                                        @foreach ($invoice->statements as $key => $statement)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $statement->created_at->format('d F Y') }}</td>
                                                <td>Payment</td>
                                                <td>K {{ $statement->amount }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Closing Balance </b></td>
                                            <td>K
                                                {{ $invoice->details->sum('amount') - $invoice->statements->sum('amount') }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            @endforeach

                            <br>

                            @if ($student->statementsWithoutInvoice->sum('amount') > 0)

                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>

                                    </thead>
                                    <tbody>

                                        <tr>
                                            <h4>Not Invoiced</h4>
                                        </tr>

                                        @foreach ($student->statementsWithoutInvoice as $key => $statement)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $statement->created_at->format('d F Y') }}</td>
                                                <td>Payment</td>
                                                <td>K {{ $statement->amount }}</td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>
                                                - K {{ $student->statementsWithoutInvoice->sum('amount') }}.00
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            @endif

                        </div>

                        <div class="tab-pane fade show" id="invoices">
                            <div class="mb-2 d-flex justify-content-end">
                                <form action="{{ route('student.export-invoices', $student->id) }}" method="get">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-download4 mr-1 lr-lg-2"></i>
                                        <span>Export Invoices</span>
                                    </button>
                                </form>
                            </div>

                            @foreach ($student->invoices as $key => $invoice)
                                <table class="table table-bordered mb-3 mb-lg-4">
                                    <thead>
                                        <th>#</th>
                                        <th>Fee type</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <h4 class="d-flex align-items-center justify-content-between">
                                                <span>INV - {{ ++$key }}</span>

                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <form
                                                            action="{{ route('student.download-invoice', $invoice->id) }}"
                                                            method="get">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="icon-download4 mr-1 lr-lg-2"></i>
                                                                <span>PDF</span>
                                                            </button>
                                                        </form>
                                                    </div>

                                                </div>

                                            </h4>
                                        </tr>
                                        @foreach ($invoice->details as $key => $detail)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ ($detail->fee->name ?? '') }}</td>
                                                <td>K {{ $detail->amount }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td><b>Total</b></td>
                                            <td>K {{ $invoice->details->sum('amount') }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            @endforeach

                        </div>

                        <div class="tab-pane fade show" id="payment-history">

                            <table class="table table-bordered">
                                <thead>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </thead>
                                <tbody>
                                    @foreach ($student->receipts as $key => $receipt)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $receipt->created_at->format('d F Y') }}</td>
                                            <td>K {{ $receipt->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Manage user information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item"><a href="#account-information" class="nav-link active"
                                data-toggle="tab">Account Info</a></li>
                        <li class="nav-item"><a href="#academic-info" class="nav-link" data-toggle="tab">Academic
                                Info</a></li>
                        <li class="nav-item"><a href="#profile-information" class="nav-link" data-toggle="tab">Personal
                                Info</a></li>
                        <li class="nav-item"><a href="#next-of-kin-info" class="nav-link" data-toggle="tab">Next of Kin
                                Info</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="account-information">
                            <!-- Add your account info content here -->
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="ajax-update" data-reload="#page-header" method="post"
                                        action="{{ route('students.resetAccountPassword', $student->user->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Password: <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <input value="{{ old('password') }}" required class="form-control"
                                                    placeholder="Password" name="password" type="password">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Confirm Password:
                                                <span class="text-danger">*</span></label>
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
                                                <input required class="form-control" placeholder="Confirm Password"
                                                    name="force_password_reset" type="checkbox">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-9">
                                                <input value="{{ $student->user->id }}" required hidden
                                                    class="form-control" name="user_id" type="number">
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

                        <div class="tab-pane fade" id="academic-info">

                            <!-- Add your academic info content here -->
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="ajax-update" data-reload="#page-header" method="post"
                                        action="{{ route('students.update', $student->user->id) }}">
                                        @csrf @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="program_id">Program: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Program" required
                                                        class="select-search form-control" name="program_id"
                                                        id="program_id">
                                                        <option value=""></option>
                                                        @foreach ($programs as $program)
                                                            <option value="{{ $program->id }}"
                                                                {{ $student->program_id === $program->id ? 'selected' : '' }}>
                                                                {{ $program->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="academic_period_intake_id">Academic Period Intake: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Academic Period Intake" required
                                                        class="select-search form-control"
                                                        name="academic_period_intake_id" id="academic_period_intake_id">
                                                        <option value=""></option>
                                                        @foreach ($periodIntakes as $intake)
                                                            <option value="{{ $intake->id }}"
                                                                {{ $student->academic_period_intake_id === $intake->id ? 'selected' : '' }}>
                                                                {{ $intake->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="study_mode_id">Study Mode: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Study Mode" required
                                                        class="select-search form-control" name="study_mode_id"
                                                        id="study_mode_id">
                                                        <option value=""></option>
                                                        @foreach ($studyModes as $mode)
                                                            <option value="{{ $mode->id }}"
                                                                {{ $student->study_mode_id === $mode->id ? 'selected' : '' }}>
                                                                {{ $mode->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="course_level_id">Course Level: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Course Level" required
                                                        class="select-search form-control" name="course_level_id"
                                                        id="course_level_id">
                                                        <option value=""></option>
                                                        @foreach ($course_levels as $level)
                                                            <option value="{{ $level->id }}"
                                                                {{ $student->course_level_id === $level->id ? 'selected' : '' }}>
                                                                {{ $level->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="period_type_id">Period Type: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Period Type" required
                                                        class="select-search form-control" name="period_type_id"
                                                        id="period_type_id">
                                                        <option value=""></option>
                                                        @foreach ($periodTypes as $type)
                                                            <option value="{{ $type->id }}"
                                                                {{ $student->period_type_id === $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="admission_year">Admission Year: <span
                                                            class="text-danger">*</span></label>
                                                    <input value="{{ $student->admission_year }}" required
                                                        type="year" name="admission_year" class="form-control"
                                                        placeholder="Admission Year">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="graduated">Graduated: <span
                                                            class="text-danger">*</span></label>
                                                    <select class="select form-control" required name="graduated"
                                                        data-fouc data-placeholder="Select Graduation Status">
                                                        <option value=""></option>
                                                        <option value="1"
                                                            {{ $student->graduated === 1 ? 'selected' : '' }}>Yes</option>
                                                        <option value="0"
                                                            {{ $student->graduated === 0 ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-left">
                                            <button type="submit" class="btn btn-primary">Submit form <i
                                                    class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="profile-information">

                            <!-- Add your profile info content here -->
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="ajax-update" data-reload="#page-header" method="post"
                                        action="{{ route('students.update', $student->user->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name: <span class="text-danger">*</span></label>
                                                    <input value="{{ $student->user->first_name }}" required
                                                        type="text" name="first_name" placeholder="First Name"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Middle Name:</label>
                                                    <input value="{{ $student->user->middle_name }}" type="text"
                                                        name="middle_name" placeholder="Middle Name"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Last Name: <span class="text-danger">*</span></label>
                                                    <input value="{{ $student->user->last_name }}" required
                                                        type="text" name="last_name" placeholder="Last Name"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email Address: <span class="text-danger">*</span></label>
                                                    <input value="{{ $student->user->email }}" required
                                                        class="form-control" placeholder="Email Address" name="email"
                                                        type="text">
                                                </div>
                                            </div>

                                            <!-- Add more rows as needed -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lga_id">Town: <span class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Town" required
                                                        class="select-search form-control" name="town_id" id="lga_id">
                                                        <option value=""></option>
                                                        @foreach ($towns as $town)
                                                            <option value="{{ $town->id }}"
                                                                {{ $town->id == $student->user->userPersonalInfo->town->id ? 'selected' : '' }}>
                                                                {{ $town->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="province_id">Province: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Province" required
                                                        class="select-search form-control" name="province_id"
                                                        id="province_id">
                                                        <option value=""></option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}"
                                                                {{ $province->id == $student->user->userPersonalInfo->province->id ? 'selected' : '' }}>
                                                                {{ $province->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="country_id">Country: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Country" required
                                                        class="select-search form-control" name="country_id"
                                                        id="country_id">
                                                        <option value=""></option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}"
                                                                {{ $country->id == $student->user->userPersonalInfo->country->id ? 'selected' : '' }}>
                                                                {{ $country->country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="d-block">Upload Passport Photo:</label>
                                                    <input value="{{ old('photo') }}" accept="image/*" type="file"
                                                        name="photo" class="form-input-styled" data-fouc>
                                                    <span class="form-text text-muted">Accepted Images: jpeg, png. Max file
                                                        size 2Mb</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-left">
                                            <button type="submit" class="btn btn-primary">Submit form <i
                                                    class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="next-of-kin-info">

                            <!-- Add your next of kin info content here -->
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="ajax-update" data-reload="#page-header" method="post"
                                        action="{{ route('students.update', $student->user->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Full Name: <span class="text-danger">*</span></label>
                                                    <input value="{{ $student->user->userNextOfKin->full_name }}"
                                                        required type="text" name="kin_full_name"
                                                        placeholder="Full Name" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Phone: <span class="text-danger">*</span></label>
                                                    <input value="{{ $student->user->userNextOfKin->phone }}" required
                                                        type="text" name="kin_mobile" class="form-control"
                                                        placeholder="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Telephone:</label>
                                                    <input value="{{ $student->user->userNextOfKin->telephone }}"
                                                        type="text" name="kin_telephone" class="form-control"
                                                        placeholder="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kin_relationship_id">Relationship: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Relationship" required
                                                        class="select-search form-control" name="kin_relationship_id"
                                                        id="kin_relationship_id">
                                                        <option value=""></option>
                                                        @foreach ($relationships as $relationship)
                                                            <option value="{{ $relationship->id }}"
                                                                {{ $relationship->id == $student->user->userNextOfKin->relationship->id ? 'selected' : '' }}>
                                                                {{ $relationship->relationship }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kin_town_id">Town: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Town" required
                                                        class="select-search form-control" name="kin_town_id"
                                                        id="kin_town_id">
                                                        <option value=""></option>
                                                        @foreach ($towns as $town)
                                                            <option value="{{ $town->id }}"
                                                                {{ $town->id == $student->user->userNextOfKin->town->id ? 'selected' : '' }}>
                                                                {{ $town->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kin_province_id">Province: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Province" required
                                                        class="select-search form-control" name="kin_province_id"
                                                        id="kin_province_id">
                                                        <option value=""></option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}"
                                                                {{ $province->id == $student->user->userNextOfKin->province->id ? 'selected' : '' }}>
                                                                {{ $province->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kin_country_id">Country: <span
                                                            class="text-danger">*</span></label>
                                                    <select data-placeholder="Select Country" required
                                                        class="select-search form-control" name="kin_country_id"
                                                        id="kin_country_id">
                                                        <option value=""></option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}"
                                                                {{ $country->id == $student->user->userNextOfKin->country->id ? 'selected' : '' }}>
                                                                {{ $country->country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-left">
                                            <button type="submit" class="btn btn-primary">Submit form <i
                                                    class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

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
                        @foreach ($results as $innerIndex => $academicData)
                            <li class="nav-item {{ $innerIndex == 0 ? 'active' : '' }}">
                                <a href="#results-{{ $academicData['academic_period_id'] }}" class="nav-link"
                                    data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                            </li>
                        @endforeach
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        @foreach ($results as $innerIndex => $academicData)
                            <div class="tab-pane fade {{ $innerIndex == 0 ? 'show active' : '' }}"
                                id="results-{{ $academicData['academic_period_id'] }}">
                                <h5 class="p-2">
                                    <strong>{{ $academicData['academic_period_code'] . ' - ' . $academicData['academic_period_name'] }}</strong>
                                </h5>
                                <h5 class="p-2"><strong>{{ $student->id }}</strong></h5>
                                <table class="table table-hover table-striped-columns mb-3">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Course Code</th>
                                            <th>Course Name</th>
                                            <th>Mark</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($academicData['grades'] as $course)
                                            <tr>
                                                <th>{{ $loop->iteration }}</th>
                                                <td>{{ $course['course_code'] }}</td>
                                                <td>{{ $course['course_title'] }}</td>
                                                <td> {{ $course['total'] }}</td>
                                                <td>{{ $course['grade'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                <p class="bg-success p-3 align-bottom">Comment
                                    : {{ $academicData['comments']['comment'] }}</p>
                                <hr>

                            </div>
                        @endforeach
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
                    <h6 class="card-title">CA Results Information </h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        @foreach ($caresults as $innerIndex => $academicData)
                            <li class="nav-item {{ $innerIndex == 0 ? 'active' : '' }}">
                                <a href="#results-{{ $academicData['academic_period_id'] }}" class="nav-link"
                                    data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                            </li>
                        @endforeach
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        @foreach ($results as $innerIndex => $academicData)
                            <div class="tab-pane fade {{ $innerIndex == 0 ? 'show active' : '' }}"
                                id="results-{{ $academicData['academic_period_id'] }}">
                                <h5 class="p-2">
                                    <strong>{{ $academicData['academic_period_code'] . ' - ' . $academicData['academic_period_name'] }}</strong>
                                </h5>
                                <h5 class="p-2"><strong>{{ $student->id }}</strong></h5>
                                <table class="table table-hover table-striped-columns mb-3">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Course Code</th>
                                            <th>Course Name</th>
                                            <th>Mark</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($academicData['grades'] as $course)
                                            <tr>
                                                <th>{{ $loop->iteration }}</th>
                                                <td>{{ $course['course_code'] }}</td>
                                                <td>{{ $course['course_title'] }}</td>
                                                <td> {{ $course['total'] }}</td>
                                                <td>{{ $course['grade'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                <p class="bg-success p-3 align-bottom">Comment
                                    : {{ $academicData['comments']['comment'] }}</p>
                                <hr>

                            </div>
                        @endforeach
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

            @if ($isRegistered)

                <div class="card card-collapsed">

                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">Registration Summary</h6>
                        {!! Qs::getPanelOptions() !!}
                    </div>

                    <div class="card-body">

                        <form action="{{ route('registration.summary') }}" method="get">
                            @csrf
                            <input name="academic_period_id" type="hidden"
                                value="{{ $student->academic_info->academic_period_id }}" />
                            <input name="student_number" type="hidden" value="{{ $student->id }}" />
                            <button type="submit" class="btn btn-primary mt-2">Download summary</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">Courses available for registration</h6>
                        {!! Qs::getPanelOptions() !!}
                    </div>
                    @if ($courses)
                        <div class="card-body">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $course->code }}</td>
                                            <td>{{ $course->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($isWithinRegistrationPeriod)
                                @if (!$isRegistered)
                                    <form action="{{ route('enrollments.store') }}" method="post">
                                        @csrf
                                        <input name="student_number" type="hidden" value="{{ $student->id }}" />
                                        <button id="ajax-btn" type="submit"
                                            class="btn btn-primary mt-2">Register</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @else
                        <div class="container ">
                            <h6> No courses available</h6>
                            <p><i>tip - student either has no invoice or is not within the registration period.</i> </p>
                        </div>
                    @endif
                </div>

            @endif

        </div>

    </div>
@endsection
