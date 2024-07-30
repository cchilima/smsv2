@extends('layouts.master')
@section('page_title', 'Update Student Class')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Class Assessment Exams Manager</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Student ID</th>
                                <th>Names</th>
                                <th>Course</th>
                                <th>Assessment Type</th>
                                <th>Marks</th>
{{--                                <th>Action</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->studentID }}</td>
                                    <td>{{ $result->first_name.' '.$result->last_name }}</td>
                                    <td>{{ $result->code.' - '.$result->title }}</td>
                                    <td>{{ $result->name }}</td>
{{--                                    <td>{{ $result->total }}</td>--}}
                                    <td class="{{ ($result->status == 0 ? 'edit-total-link' : '') }}">
                                    <span class="display-mode"
                                          id="display-mode{{ Qs::hash($result->id) }}">{{ $result->total }}</span>
                                        <input type="text" class="edit-mode form-control"
                                               id="class{{ Qs::hash($result->id) }}"
                                               value="{{ $result->total }}" style="display: none;" onchange="updateExamResultsToPublish('{{ Qs::hash($result->id) }}')">
                                    </td>
{{--                                    <td class="text-center">--}}
{{--                                        <div class="list-icons">--}}
{{--                                            <div class="dropdown">--}}
{{--                                                <a href="#" class="list-icons-item" data-toggle="dropdown">--}}
{{--                                                    <i class="icon-menu9"></i>--}}
{{--                                                </a>--}}

{{--                                                <div class="dropdown-menu dropdown-menu-left">--}}

{{--                                                    <a href="#" class="dropdown-item edit-total-link"><i--}}
{{--                                                                class="icon-pencil"></i> Edit</a>--}}

{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
    </div>

@endsection
