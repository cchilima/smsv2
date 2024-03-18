@extends('layouts.master')
@section('page_title', 'My Applications')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp


    <div class="card">

        <div class="card-header bg-white header-elements-inline">
            <h6 class="card-title"> </h6>


            <table class="table datatable-button-html6-columns">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                                    <td>{{ $application->program->name ?? ''}}</td>
                                    <td>{{ $application->status ?? '' }}</td>
                                    <td>{{ $application->created_at ?? '' }}</td>
                                    <td class="text-center">
                                        <div>
                                             <a href="/application/step-2/{{$application->id}}" class="dropdown-item"><i class="icon-pencil"></i>view</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

        </div>

    </div>


@endsection
