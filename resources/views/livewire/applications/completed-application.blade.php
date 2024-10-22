@section('page_title', 'Student Application')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Student Application</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#application-info" class="nav-link active" data-toggle="tab">Application
                    Information</a>
            </li>
            <li class="nav-item"><a href="#actions" class="nav-link" data-toggle="tab">Actions</a>
            </li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="application-info">
                <div class="mt-4">

                    <h5 class="mt-4 mb-3">Student Information</h5>

                    <table class="table table-striped">
                        <tbody class="light-deca">
                            <tr>
                                <th>First Name <span class="text-danger">*</span></th>
                                <td>{{ $application->first_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Middle Name</th>
                                <td>{{ $application->middle_name ?? ' ' }}</td>
                            </tr>
                            <tr>
                                <th>Last Name <span class="text-danger">*</span></th>
                                <td>{{ $application->last_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Gender <span class="text-danger">*</span></th>
                                <td>{{ $application->gender ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth <span class="text-danger">*</span></th>
                                <td>{{ $application->date_of_birth ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email Address <span class="text-danger">*</span></th>
                                <td>{{ $application->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile <span class="text-danger">*</span></th>
                                <td>{{ $application->phone_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Country <span class="text-danger">*</span></th>
                                <td>{{ $application->country->country ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Province <span class="text-danger">*</span></th>
                                <td>{{ $application->province->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Town <span class="text-danger">*</span></th>
                                <td>{{ $application->town->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Address <span class="text-danger">*</span></th>
                                <td>{{ $application->address ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="mt-4 mb-3">Next of Kin Information</h5>

                    <table class="table table-striped">
                        <tbody class="light-deca">
                            <tr>
                                <th>Full Name <span class="text-danger">*</span></th>
                                <td>{{ $application->nextOfKin->full_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile <span class="text-danger">*</span></th>
                                <td>{{ $application->nextOfKin->mobile ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telephone </th>
                                <td>{{ $application->nextOfKin->telephone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Country <span class="text-danger">*</span></th>
                                <td>{{ $application->nextOfKin->country->country ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Province <span class="text-danger">*</span></th>
                                <td>{{ $application->nextOfKin->province->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Town <span class="text-danger">*</span></th>
                                <td>{{ $application->nextOfKin->town->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Address <span class="text-danger">*</span></th>
                                <td>{{ $application->nextOfKin->address ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="mt-4 mb-3">Academic Information</h5>

                    <table class="table table-striped">
                        <tbody class="light-deca">
                            <tr>
                                <th>Year Applied For <span class="text-danger">*</span></th>
                                <td>{{ $application->year_applying_for ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Program <span class="text-danger">*</span></th>
                                <td>{{ $application->program->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Intake <span class="text-danger">*</span></th>
                                <td>{{ $application->intake->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Study Mode <span class="text-danger">*</span></th>
                                <td>{{ $application->study_mode->name ?? '-' }}</td>
                            </tr>
                            @if ($application->attachment)
                                <tr>
                                    <th>{{ $application->attachment->type }} <span class="text-danger">*</span></th>
                                    <td>
                                        <form
                                            action="{{ route('application.download_attachment', $application->attachment->id) }}"
                                            method="GET">
                                            @csrf @method('GET')
                                            <button type="submit" class="btn btn-primary">Download</button>
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

                    <h5 class="mt-4 mb-3">Grades</h5>

                    @if (count($application->grades) > 0)
                        <table class="table table-striped">
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

            <div wire:ignore.self class="tab-pane fade show" id="actions">
                <table class="table table-striped">
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
                                                    class="btn btn-primary">
                                                    Download Letter
                                                </a>
                                            @else
                                                Please visit our admissions team to guide you on next steps as
                                                you haven't
                                                meet the set candidate criteria for admission.
                                            @endif
                                        @else
                                            Please complete the full application fee payment to download the
                                            provisional
                                            letter.
                                        @endif
                                    </td>
                                @else
                                    <td>NA</td>
                                @endif
                            </tr>
                        @else
                            <tr>
                                <td>Application incomplete</td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                @auth
                    @if (Qs::userIsSuperAdmin() || Qs::userIsAdmin())

                        @if ($application->status !== 'rejected' && $application->status !== 'accepted')
                            @if ($application->status == 'complete')
                                <div class="d-flex">
                                    @if ($isEligibleForProvisonal)
                                        <a class="btn btn-primary text-white d-flex align-items-center"
                                            wire:loading.attr="disabled"
                                            wire:confirm="Are you sure you want to accept this student?"
                                            wire:click="accept()">Accept
                                            Student <span wire:loading wire:target="accept()"
                                                class="ml-2 loading-spinner"></span></a>
                                    @endif

                                    <a class="ml-2 btn btn-danger text-white d-flex align-items-center"
                                        wire:loading.attr="disabled"
                                        wire:confirm="Are you sure you want to reject this student?" wire:click="reject()">
                                        Reject Student <span wire:loading wire:target="reject()"
                                            class="ml-2 loading-spinner"></span></a>
                                </div>
                            @endif
                        @else
                            <div class="">
                                <p>This student has already been {{ $application->status }}.</p>
                            </div>
                        @endif
                    @endif

                @endauth

            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        .loading-spinner {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 2px solid #fff;
            border-top: 2px solid transparent;
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
