@extends('layouts.master')
@section('page_title', 'Prerequisites')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Prerequisites</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-prerequisite" class="nav-link active" data-toggle="tab">Manage
                        Prerequisites</a></li>
                <li class="nav-item"><a href="#new-prerequisite-courses" class="nav-link" data-toggle="tab"><i
                            class="icon-plus2"></i> Create New Prerequisites</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-prerequisite">
                    <livewire:datatables.academics.prerequisites />

                    {{-- <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Course</th>
                                <th>Prerequisite</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $c)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $c->code . ' ' . $c->name }}</td>
                                    <td>
                                        <table class="table table-bordered">
                                            <tbody>
                                                @foreach ($c->prerequisites as $p)
                                                    <tr>
                                                        <td>{{ $p->code . ' - ' . $p->name }}</td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if (Qs::userIsTeamSA())
                                                        <a href="{{ route('prerequisites.edit', Qs::hash($c->id)) }}"
                                                            class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    @if (Qs::userIsSuperAdmin())
                                                        <a id="{{ $c->pid }}" onclick="confirmDelete(this.id)"
                                                            href="#" class="dropdown-item"><i class="icon-trash"></i>
                                                            Delete</a>
                                                        <form method="post" id="item-delete-{{ $c->id }}"
                                                            action="{{ route('prerequisites.destroy', Qs::hash($c->id)) }}"
                                                            class="hidden">@csrf @method('delete')</form>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                </div>

                <div class="tab-pane fade" id="new-prerequisite-courses">

                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('prerequisites.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label for="courses" class="col-lg-3 col-form-label font-weight-semibold">Courses <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select Course" class="form-control select-search"
                                            name="course_id" id="courses">
                                            <option value=""></option>
                                            @foreach ($pcourses as $c)
                                                <option value="{{ $c->id }}">{{ $c->code . ' - ' . $c->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="course-level"
                                        class="col-lg-3 col-form-label font-weight-semibold">Prerequisite Courses <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select Prerequisite" multiple
                                            class="form-control select" name="prerequisite_course_id[]" id="course-level">
                                            @foreach ($pcourses as $c)
                                                <option value="{{ $c->id }}">{{ $c->code . ' - ' . $c->name }}
                                                </option>
                                            @endforeach
                                        </select>
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

    {{-- Class List Ends --}}

@endsection
