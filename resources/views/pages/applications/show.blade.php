@extends('layouts.master')
@section('page_title', 'Application')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">

        <div class="card-header bg-white header-elements-inline">

            {!! Qs::getPanelOptions() !!}

        </div>

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
            <h5>Attachments</h5>

            @if (count($application->attachments) > 0)
                @foreach ($application->attachments as $attachment)
                    <div class="d-flex align-items-center mb-3">
                        <span class="d-inline-block mr-3">{{ $attachment->type }}</span>
                        <form action={{ route('application.download_attachment', $attachment->id) }} method="GET">
                            @csrf @method('GET')
                            <button type="submit" class="btn btn-primary">Download</button>
                        </form>
                    </div>
                @endforeach
            @else
                <p>No attachments added</p>
            @endif

            <!-- Display attachments if any -->
        </div>
        <br><br><br>
    </div>
@endsection
