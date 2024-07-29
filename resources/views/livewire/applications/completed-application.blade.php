<div class="container mt-10 mb-10">
    
    <ul class="custom-tabs align-left">
        <li class="{{ $currentSection === 'application' ? 'active' : '' }} custom-tab-item"
            wire:click="setSection('application')">
            <a>Application Details</a>
        </li>
        <li class="{{ $currentSection === 'provisional' ? 'active' : '' }} custom-tab-item"
            wire:click="setSection('provisional')">
            <a>Provisional Letter</a>
        </li>
    </ul>


@if($currentSection == 'application')

    <div class="mt-4">

    <b class="flow-text light-deca">Student Information</b>

    <div class="white rounded-md z-depth-1 p-10 mt-1">
    <table class="striped">
        <tbody class="light-deca">
            <tr>
                <th>First Name</th>
                <td>{{ $application->first_name ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Middle Name</th>
                <td>{{ $application->middle_name ?? ' ' }}</td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td>{{ $application->last_name ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>{{ $application->gender ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{{ $application->date_of_birth ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td>{{ $application->email ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{ $application->phone_number ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Country</th>
                <td>{{ $application->country->country ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Province</th>
                <td>{{ $application->province->name ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Town</th>
                <td>{{ $application->town->name ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $application->address ?? 'Missing' }}</td>
            </tr>
        </tbody>
    </table>

    </div>



    <br><br>

    <b class="flow-text light-deca">Next of Kin</b>


    <div class="white rounded-md z-depth-1 p-10 mt-1">
    <table class="striped">
        <tbody class="light-deca">
            <tr>
                <th>Name</th>
                <td>{{ $application->nextOfKin->full_name ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $application->nextOfKin->address ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{ $application->nextOfKin->mobile ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Other mobile </th>
                <td>{{ $application->nextOfKin->telephone ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Residency</th>
                @if( $application->nextOfKin)
                <td>{{ $application->nextOfKin->town->name}} , {{ $application->nextOfKin->province->name}} {{ $application->nextOfKin->country->country}}</td>
                @endif
            </tr>
        </tbody>
    </table>
</div>

    <br><br>

    <b class="flow-text light-deca">Academic Information</b>


    <div class="white rounded-md z-depth-1 p-10 mt-1">
    <table class="striped">
        <tbody class="light-deca">
            <tr>
                <th>Program</th>
                <td>{{ $application->program->name ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Academic Period Intake</th>
                <td>{{ $application->intake->name ?? 'Missing' }}</td>
            </tr>
            <tr>
                <th>Study Mode</th>
                <td>{{ $application->study_mode->name ?? 'Missing' }}</td>
            </tr>
            @if ($application->attachment)
                <tr>
                    <th>{{ $application->attachment->type }}</th>
                    <td>
                        <form action="{{ route('application.download_attachment', $application->attachment->id) }}" method="GET">
                            @csrf @method('GET')
                            <button type="submit" class="btn btn-small btn-floating indigo darken-3 waves-effect waves-light"><i class="material-icons">arrow_downward</i></button>
                        </form>
                    </td>
                </tr>
            @else
                <tr>
                    <td>No attachments added</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>


<br><br>

<b class="flow-text light-deca">Grades</b>


<div class="white rounded-md z-depth-1 p-10 mt-1">


@if (count($application->grades) > 0)


<table class="rounded white z-depth-0 centered striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Grade</th>

        </tr>
    </thead>

    <tbody class="light-deca">
        @foreach ($application->grades as $key => $grade)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $grade->subject }}</td>
                <td>{{ $grade->grade }}</td>
            </tr>
        @endforeach

    </tbody>
</table>

@endif



</div>

</div>

@else

<div class="white rounded-md z-depth-1 p-10 mt-4">


<table class="striped">
    <tbody>
        @if ($application->attachment)
            <tr>
                <td>Application fee payment</td>
                <td>
                    K {{ $application->payment->sum('amount') ?? 'K0' }}
                </td>
            </tr>
            <tr>
                <td>Provisional letter</td>
                <td>
                    @if($application->status == 'complete' || $application->status == 'accepted')
                        <form action="{{ route('application.download_provisional') }}" method="GET">
                            @csrf @method('GET')
                            <input name="applicant_id" hidden type="text" value="{{ $application->id }}">
                            <button type="submit" class="btn btn-small black waves-effect waves-light rounded">Download</button>
                        </form>
                    @else
                        Please complete the full application fee payment to download the provisional letter.
                    @endif
                </td>
            </tr>
        @else
            <tr>
                <td>No attachments added</td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>


</div>



@if($application->status !== 'rejected' && $application->status !== 'accepted' )

@if($application->status == 'complete')
<div class="white rounded-md z-depth-1 p-10 mt-4">
    <a class="btn btn-small black rounded" wire:click="accept()">accept student</a>
    <a class="btn btn-small red rounded" wire:click="reject()">reject student</a>
</div>
@endif

@else

<div class="white rounded-md z-depth-1 p-10 mt-4">
  <p>This student has already been {{ $application->status }}.</p>
</div>

@endif


@endif



</div>


@script
    <script>
        $wire.on('accepted', () => {
            M.toast({
                html: 'prospective student formally accepted.'
            })
        });

        $wire.on('rejected', () => {
            M.toast({
                html: 'prospective student formally rejected.'
            })
        });
    </script>
@endscript
