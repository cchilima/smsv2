@php
    use App\Helpers\Qs;
@endphp

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

    @if ($currentSection == 'application')

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
                            @if ($application->nextOfKin)
                                <td>{{ $application->nextOfKin->town->name }} ,
                                    {{ $application->nextOfKin->province->name }}
                                    {{ $application->nextOfKin->country->country }}</td>
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
                            <th>Applied for year</th>
                            <td>{{ $application->year_applying_for ?? 'Missing' }}</td>
                        </tr>
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
                                    <form
                                        action="{{ route('application.download_attachment', $application->attachment->id) }}"
                                        method="GET">
                                        @csrf @method('GET')
                                        <button type="submit"
                                            class="btn btn-small btn-floating indigo darken-3 waves-effect waves-light"><i
                                                class="material-icons">arrow_downward</i></button>
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
                            @if ($application->status != 'rejected')
                                <td>
                                    @if ($application->status == 'complete' || $application->status == 'accepted')

                                        @if ($isEligibleForProvisonal)
                                            <a href="{{ route('application.download_provisional', ['applicant_id' => $application->id]) }}"
                                                class="btn btn-small black waves-effect waves-light rounded">
                                                <i class="material-icons right">arrow_downward</i> Provisional Letter
                                            </a>
                                        @else
                                            Please visit our admissions team to guide you on next steps as you haven't
                                            meet the set candidate criteria for admission.
                                        @endif
                                    @else
                                        Please complete the full application fee payment to download the provisional
                                        letter.
                                    @endif
                                </td>
                            @else
                                <td>NA</td>
                            @endif
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

        @auth
            @if (Qs::userIsSuperAdmin() || Qs::userIsAdmin())

                @if ($application->status !== 'rejected' && $application->status !== 'accepted')

                    @if ($application->status == 'complete')
                        <div class="white rounded-md z-depth-1 p-10 mt-4">
                            @if ($isEligibleForProvisonal)
                                <a class="btn btn-small black rounded" wire:loading.attr="disabled"
                                    wire:click="accept()">Accept
                                    Student <span wire:loading wire:target="accept()" class="loading-spinner"></span></a>
                            @endif

                            <a class="btn btn-small red rounded" wire:loading.attr="disabled" wire:click="reject()">
                                Reject Student <span wire:loading wire:target="reject()" class="loading-spinner"></span></a>
                        </div>
                    @endif
                @else
                    <div class="white rounded-md z-depth-1 p-10 mt-4">
                        <p>This student has already been {{ $application->status }}.</p>
                    </div>

                @endif
            @endif

        @endif
    @endauth
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

@push('css')
    <style>
        .loading-spinner {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #fff;
            border-top: 2px solid #020843;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
