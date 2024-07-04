
@extends('layouts.master')
@section('page_title', 'Application')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Applications</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#application" class="nav-link active" data-toggle="tab">Application</a></li>
                <li class="nav-item"><a href="#provisional-letter" class="nav-link" data-toggle="tab">Provisional letter</a></li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="application">





                    <div class="card">

                        <div class="card-header bg-white header-elements-inline">
                            {!! Qs::getPanelOptions() !!}
                        </div>

                        <div class="container mt-4">
                            <h5><b>Student Information</b></h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>First Name</th>
                                        <td>{{ $application->first_name ?? 'Missing' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Middle Name</th>
                                        <td>{{ $application->middle_name ?? 'Missing' }}</td>
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

                            <br><br>

                            <h5><b>Academic Information</b></h5>
                            <table class="table table-bordered">
                                <tbody>
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
                                                <td>{{ $application->attachment->type }}</td>
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
                            <br><br><br>
                        </div>
                    </div>






                </div>

                <div class="tab-pane fade show" id="provisional-letter">



                <table class="table table-bordered">
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
                                                    @if($application->status == 'complete')
                                                    <form
                                                        action="{{ route('application.download_provisional') }}"
                                                        method="GET">
                                                        @csrf @method('GET')
                                                        <input name="applicant_id" hidden type="text" value="{{$application->id}}">
                                                        <button type="submit" class="btn btn-primary">Download</button>
                                                    </form>
                                                    @else
                                                    Make full application fee payment to download letter.
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
            </div>
        </div>
    </div>

@endsection







