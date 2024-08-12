@extends('layouts.master')
@section('page_title', 'Program results')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Download Student results per program</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <livewire:datatables.academics.assessments.program-lists :academicPeriodId="$academicPeriodId" />

            <table class="table datatable-button-html5-columns">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Program Code</th>
                        <th>Program Name</th>
                        <th>Qualification</th>
                        <th>Department</th>
                        <th>Students</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programs as $p)
                        @if ($p->students_count > 0)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->code }}</td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->qualification->name }}</td>
                                <td>{{ $p->department->name }}</td>
                                <td>{{ $p->students_count }}</td>

                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                {{--                                        @if (Qs::userIsTeamSA()) --}}
                                                {{--                                            <a href="{{ route('student.one.program.list', ['ac' => $academicPeriod->id, 'pid' => $p->id]) }}" --}}
                                                {{--                                               class="dropdown-item"><i class="icon-paperplane"></i> --}}
                                                {{--                                                Download PDF List</a> --}}
                                                <a href="{{ route('student.download.result.list', $p->id) }}"
                                                    class="dropdown-item"><i class="icon-paperplane"></i>
                                                    Download CSV List</a>
                                                {{--                                        @endif --}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- Student List Ends --}}

@endsection
