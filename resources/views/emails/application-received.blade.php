@php
    use app\Helpers\Qs;
@endphp

@extends('layouts.email_template')
@section('content')
    <div class="content">
        <p class="flow-text light-deca">ZUT Application</p>

        @if ($addressed_to == 'admissions')
            <p class="light-deca">
                Dear Admissions Team,
                <br><br>
                A new application has been submitted by a prospective student. Please review the details and proceed with
                the approval or rejection process.
                <br><br>
                <strong class="flow-text">Application Details</strong><br>
                <strong>Applicant Name: </strong> {{ $application->first_name }} {{ $application->last_name }} <br>
                <strong>Program: </strong> {{ $application->program->name }}<br>
                <strong>Application Code:</strong> {{ $application->applicant_code }}
                <br><br>
                Best regards,<br>
                ZUT.
            </p>
        @else
            <p class="light-deca">
                Dear {{ $application->first_name }} {{ $application->last_name }},
                <br><br>
                We have received your application for the {{ $application->program->name }} program. Our admissions team is
                currently reviewing your submission, and we will notify you of our decision in the coming days.
                <br><br>
                Thank you for your interest in ZUT. We appreciate your patience during this process.
                <br><br>
                Best regards,<br>
                The ZUT Admissions Team
            </p>
        @endif

    </div>
@endsection
