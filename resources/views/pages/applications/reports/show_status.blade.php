@extends('layouts.master')
@section('page_title', 'Student Applications')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header bg-white ">
            <h6 class="card-title">Applications</h6>

            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Program</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $application->first_name ?? '' }} {{ $application->last_name ?? '' }}</td>
                        <td>{{ $application->program->name ?? '' }}</td>
                        <td>{{ $application->status ?? '' }}</td>
                        <td>{{ $application->created_at ?? '' }}</td>

                        <td class="">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a href="{{ route('application.show', $application->id) }}"
                                           class="dropdown-item"><i class="icon-eye"></i>View</a>

                                        @if ($application->status === 'incomplete')
                                            <a href="/application/step-2/{{ $application->id }}"
                                               class="dropdown-item"><i class="icon-paperplane"></i>Continue
                                                application</a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>

@endsection
