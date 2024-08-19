@extends('layouts.email_template')
@section('content')

        <div class="content">
            <p class="flow-text light-deca">ZUT Application</p>

            @if($application_status == 'rejected')
            <p class="light-deca">
                Hi {{ $application->first_name }} {{ $application->last_name }} ,we regret to inform you that your application to the ZUT program has not been successful this time. 
                <br><br>
                We received a large number of applications, and while your profile was impressive, we are unable to move forward with your application. 
                We encourage you to apply again in the future and wish you the best in your future endeavors.
                <br><br>
                Thank you for your interest in the {{ $application->program->name }} program.
            </p>

            @else

            <p class="light-deca">
                Hi {{ $application->first_name }} {{ $application->last_name }} a big congratulations to you, we are pleased to inform you that your application to the {{ $application->program->name }} program has been accepted. 
                <br><br>
                We were highly impressed with your qualifications and experience, and we believe you will be a valuable addition to our program. 
                Please look out for further instructions in the coming days regarding the next steps.
                <br><br>
                Welcome to the {{ $application->program->name }} program.
            </p>
            @endif
        </div>

@endsection
