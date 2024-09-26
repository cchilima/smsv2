@extends('layouts.master')
@section('page_title', 'Student Profile - ' . \Illuminate\Support\Facades\Auth::user()->first_name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card text-center">
                <div class="card-body p-4">

                    @php
                        $passportPhotoUrl = !auth()->user()->userPersonalInfo?->passport_photo_path
                            ? asset('images/default-avatar.png')
                            : asset(auth()->user()->userPersonalInfo?->passport_photo_path);
                    @endphp

                    <div class="rounded-circle w-100 h-100">
                        <img style="aspect-ratio: 1/1; object-fit: cover" src="{{ $passportPhotoUrl }}"
                            alt="User passport photo" class="rounded-circle w-100 h-100">
                    </div>
                    <br>
                    <h3 class="mt-3">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h3>
                    <h6 class="mt-1">{{ auth()->user()->student?->id }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Enrollments Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        @foreach ($enrollments as $innerIndex => $academicData)
                            <li class="nav-item">
                                <a href="#account-{{ $academicData['academic_period_id'] }}"
                                    class="nav-link @if ($loop->iteration === 1) active show @endif"
                                    data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        @foreach ($enrollments as $innerIndex => $academicData)
                            <div class="tab-pane fade @if ($loop->iteration === 1) active show @endif"
                                id="account-{{ $academicData['academic_period_id'] }}">

                                <table class="table table-hover table-striped-columns mb-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <h5 class="p-2"> Academic Period:
                                                <strong>{{ $academicData['academic_period_name'] . ' (' . $academicData['academic_period_code'] . ')' }}</strong>
                                            </h5>
                                        </div>
                                        <div>
                                            <form action="{{ route('registration.summary') }}" method="get">
                                                @csrf
                                                <input name="academic_period_id" type="hidden"
                                                    value="{{ $academicData['academic_period_id'] }}" />
                                                <input name="student_number" type="hidden"
                                                    value="{{ $academicData['student_id'] }}" />
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

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">General Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item active">
                            <a href="#account-info" class="nav-link active" data-toggle="tab">Academic Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#next-kin" class="nav-link" data-toggle="tab">Sponsor Information</a>
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
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Registered Academic Period</td>
                                        <td>{{ count($student->invoices) > 0 ? $student->invoices->last()->period->name : 'Not registered' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Semester</td>
                                        <td>{{ $student->semester }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Study Category</td>
                                        <td class="academic-infor">
                                            <span>{{ $student->study_mode->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Status</td>
                                        <td>{{ $student->admission_status == 'active' ? 'Active' : 'Inactive' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Programme Name</td>
                                        <td class="academic-infor">
                                            <span>{{ $student->program->name }}</span>
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
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Period Type</td>
                                        <td class="academic-infor">
                                            <span>{{ $student->period_type->name }}</span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="next-kin">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Full Name</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->full_name }}</span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Mobile</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->mobile }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Telephone</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->telephone }}</span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Relationship</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->relationship->relationship }}</span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Town</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->town->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Province</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->province->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Country</td>
                                        <td class="next-of-kin-infor">
                                            <span>{{ $student->user->userNextOfKin->country->country }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="profile-info">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">First Name</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->first_name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Middle Name</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->middle_name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Last Names</td>
                                        <td class="personal-infor"><span>{{ $student->user->last_name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Gender</td>
                                        <td class="personal-infor"><span>{{ $student->user->gender }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Email</td>
                                        <td class="personal-infor"><span>{{ $student->user->email }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">NRC</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->nrc }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Passport Number</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->passport }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Date of Birth</td>
                                        <td class="personal-infor">
                                            <span>{{ date('j F Y', strtotime($student->user->userPersonalInfo?->date_of_birth)) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Marital Status</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->userMaritalStatus->status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Mobile</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->mobile }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Street</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->street_main }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Town</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->town->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Province</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->province->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Country</td>
                                        <td class="personal-infor">
                                            <span>{{ $student->user->userPersonalInfo?->country->country }}</span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
