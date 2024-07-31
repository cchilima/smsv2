@extends('layouts.master')
@section('page_title', 'Student Information')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Search for a student</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <div class="row mt-3 mb-4">
                        <div class="col-md-12">
                            <form class="ajax-storexxx" method="post" action="{{ route('students.lists') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input name="query" value="{{ old('name') }}" type="text"
                                            class="form-control" placeholder="Student name">
                                    </div>
                                    <div class="col-md-1">
                                        <div class="text-right">
                                            <button id="ajax-btn" type="submit" class="btn btn-primary">Search <i
                                                    class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if (isset($users))
                        <table class="table datatable-button-html5-columns">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Student ID</th>
                                    <th>NRC</th>
                                    <th>Program</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($users as $u)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $u->first_name }}</td>
                                        <td>{{ $u->last_name }}</td>
                                        <td>{{ $u->student->id }}</td>
                                        <td>{{ $u->userPersonalInfo->nrc }}</td>
                                        <td>{{ $u->student->program->name }}</td>
                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        @if (Qs::userIsTeamSA())
                                                            <a href="#" class="dropdown-item"><i
                                                                    class="icon-pencil"></i> Edit</a>
                                                        @endif
                                                        @if (Qs::userIsTeamSA())
                                                            <a href="{{ route('show.student', $u->id, ['ac' => 1]) }}"
                                                                class="dropdown-item"><i class="icon-eye"></i> Show</a>
                                                        @endif
                                                        @if (Qs::userIsSuperAdmin())
                                                            <a id="{{ $u->id }}" onclick="confirmDelete(this.id)"
                                                                href="#" class="dropdown-item"><i
                                                                    class="icon-trash"></i> Delete</a>
                                                            <form method="post" id="item-delete-{{ $u->id }}"
                                                                action="{{ route('students.destroy', $u->id) }}"
                                                                class="hidden">@csrf @method('delete')</form>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="tab-pane fade" id="new-class">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                                <span>When a class is created, a Section will be automatically created for the class, you
                                    can edit it or add more sections to the class at <a target="_blank"
                                        href="{{ 00 }}">Manage Sections</a></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('programs.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="{{ old('name') }}" required type="text"
                                            class="form-control" placeholder="Name of Class">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Program Code <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="code" value="{{ old('code') }}" required type="text"
                                            class="form-control" placeholder="Program code">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="class_type_id"
                                        class="col-lg-3 col-form-label font-weight-semibold">Department <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select Class Type" class="form-control select"
                                            name="departmentID" id="class_type_id">
                                            {{--                                            @foreach ($departments as $d) --}}
                                            {{--                                                <option  value="{{ $d->id }}">{{ $d->name }}</option> --}}
                                            {{--                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="class_type_id"
                                        class="col-lg-3 col-form-label font-weight-semibold">Qualification <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select Class Type" class="form-control select"
                                            name="qualification_id" id="class_type_id">
                                            {{--                                            @foreach ($qualifications as $q) --}}
                                            {{--                                                <option value="{{ $q->id }}">{{ $q->name }}</option> --}}
                                            {{--                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="description" value="{{ old('description') }}" required
                                            type="text" class="form-control" placeholder="Description">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
