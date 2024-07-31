@extends('layouts.master')
@section('page_title', 'Course Registration')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    @if (session('status'))
        <?php Qs::goWithSuccessCustom(session('status')); ?>
    @endif

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
                        <td>{{ $academicInfo->academic_period->period_types->name }}</td>
                    </tr>
                    <tr>
                        <td><b>Academic Period : </b></td>
                        <td>{{ $academicInfo->academic_period->name }}</td>
                    </tr>
                    <tr>
                        <td><b>Academic Period Code : </b></td>
                        <td>{{ $academicInfo->academic_period->code }} </td>
                    </tr>
                    <tr>
                        <td><b>Study Mode : </b></td>
                        <td>{{ $academicInfo->study_mode->name }}</td>
                    </tr>
                    <tr>
                        <td><b>Registration Threshold : </b></td>
                        <td>{{ number_format($academicInfo->registration_threshold, 0) }} %</td>
                    </tr>

                    <tr>
                        <td><b>Examslip Threshold : </b></td>
                        <td>{{ number_format($academicInfo->exam_slip_threshold, 0) }} %</td>
                    </tr>

                    <tr>
                        <td><b>View Results Threshold : </b></td>
                        <td>{{ number_format($academicInfo->view_results_threshold, 0) }} %</td>
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

                <form action="{{ route('enrollments.store') }}" method="post">
                    @csrf
                    <button id="ajax-btn" type="submit" class="btn btn-primary mt-2">Register</button>
                </form>
            </div>
        </div>

    @endif

    {{-- Course List Ends --}}
@endsection
