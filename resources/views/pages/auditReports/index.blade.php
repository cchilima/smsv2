@extends('layouts.master')
@section('page_title', 'Audit reports')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Audit Reports</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-statuses" class="nav-link active" data-toggle="tab">Audit Reports</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-statuses">
                    <livewire:datatables.reports.audits />

                    {{-- <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>New values</th>
                                <th>old Values</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $audit->user->first_name . ' ' . $audit->user->last_name }}</td>
                                    <td>{{ $audit->event }}</td>
                                    <td>{{ json_encode($audit->old_values) }}</td>
                                    <td>{{ json_encode($audit->new_values) }}</td>
                                    <td>{{ date('j F Y H-i-s', strtotime($audit->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                </div>

            </div>
        </div>
    </div>

    {{-- Marital Status List Ends --}}
@endsection
