@extends('layouts.master')
@section('page_title', 'Course Registration')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    @if (session('status'))
        <?php Qs::goBackWithSuccess(session('status')); ?>
    @endif

    <div class="row">
        @if ($balancePercentage < 100 && $registrationBalance > 0)
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    <i class="icon icon-alert mr-2"></i>
                    You are not registered. Clear your balance of <strong>
                        K{{ number_format($registrationBalance, 2) }}</strong> to register for
                    {{ $academicPeriodInfo?->name }}
                </div>
            </div>
        @endif
    </div>

    <div class="card">

        <div class="card-header header-elements-inline">
            <h6 class="card-title">Academic Information</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><b>Academic Period Type :</b></td>
                        <td>{{ $academicInfo ? $academicInfo->academic_period->period_types->name : 'Not available' }}</td>
                    </tr>
                    <tr>
                        <td><b>Academic Period : </b></td>
                        <td>{{ $academicInfo ? $academicInfo->academic_period->name : 'Not available' }}</td>
                    </tr>
                    <tr>
                        <td><b>Academic Period Code : </b></td>
                        <td>{{ $academicInfo ? $academicInfo->academic_period->code : 'Not available' }} </td>
                    </tr>
                    <tr>
                        <td><b>Study Mode : </b></td>
                        <td>{{ $academicInfo ? $academicInfo->study_mode->name : 'Not available' }}</td>
                    </tr>
                    <tr>
                        <td><b>Registration Threshold : </b></td>
                        <td>{{ $academicInfo ? number_format($academicInfo->registration_threshold, 0) : '0' }} %</td>
                    </tr>
                    <tr>
                        <td><b>Registration Start Date : </b></td>
                        <td>{{ $academicInfo ? date('d M Y', strtotime($academicInfo->registration_date)) : 'Not Available' }}
                        </td>
                    </tr>
                    <tr>
                        <td><b>Late Registration Start Date : </b></td>
                        <td>{{ $academicInfo ? date('d M Y', strtotime($academicInfo->late_registration_date)) : 'Not Available' }}
                        </td>
                    </tr>
                    <tr>
                        <td><b>Late Registration End Date : </b></td>
                        <td>{{ $academicInfo ? date('d M Y', strtotime($academicInfo->late_registration_end_date)) : 'Not Available' }}
                        </td>
                    </tr>

                    <tr>
                        <td><b>Download Exam Slip Threshold : </b></td>
                        <td>{{ $academicInfo ? number_format($academicInfo->exam_slip_threshold, 0) : '0' }} %</td>
                    </tr>

                    <tr>
                        <td><b>View Results Threshold : </b></td>
                        <td>{{ $academicInfo ? number_format($academicInfo->view_results_threshold, 0) : '0' }} %</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    @if ($isRegistered)

        <div class="card">

            <div class="card-header header-elements-inline">
                <h6 class="card-title">Registration Summary</h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="card-body">
                <p>You are registered for the current academic period.</p>

                <form action="{{ route('registration.summary') }}" method="get">
                    @csrf
                    <button type="submit" class="btn btn-primary mt-2">Download summary</button>
                </form>
            </div>
        </div>
    @else
        <div class="card">
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

                    @if ($isWithinRegistrationPeriod && !$isRegistered && $registrationBalance <= 0)
                        <form action="{{ route('enrollments.store') }}" method="post">
                            @csrf
                            <input name="student_number" type="hidden" value="{{ auth()->user()->student->id }}" />
                            <button id="ajax-btn" type="submit" class="btn btn-primary mt-2">Register
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="card-body">
                    <h6> No courses available</h6>
                    <p><i>Student either has no invoice or is not within the registration period.</i></p>
                </div>
            @endif
        </div>

    @endif

    {{-- Course List Ends --}}

@endsection
