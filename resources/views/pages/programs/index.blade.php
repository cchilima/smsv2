@extends('layouts.master')
@section('page_title', 'Manage Programs')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Programs</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Program</a></li>
                <li class="nav-item"><a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New Programs</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <livewire:datatables.academics.programs />

                    <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Qualification</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programs as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->code }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->department->name }}</td>
                                    <td>{{ $p->qualification->name }}</td>
                                    <td>{{ Str::limit($p->description, 20) }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if (Qs::userIsTeamSA())
                                                        <a href="{{ route('programs.edit', $p->id) }}"
                                                            class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    @if (Qs::userIsTeamSA())
                                                        <a href="{{ route('programs.show', Qs::hash($p->id)) }}"
                                                            class="dropdown-item"><i class="icon-eye"></i> Show</a>
                                                    @endif
                                                    @if (Qs::userIsSuperAdmin())
                                                        <a id="{{ $p->id }}" onclick="confirmDelete(this.id)"
                                                            href="#" class="dropdown-item"><i class="icon-trash"></i>
                                                            Delete</a>
                                                        <form method="post" id="item-delete-{{ $p->id }}"
                                                            action="{{ route('programs.destroy', $p->id) }}"
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
                </div>

                <div class="tab-pane fade" id="new-class">
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
                                            name="department_id" id="class_type_id">
                                            @foreach ($departments as $d)
                                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
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
                                            @foreach ($qualifications as $q)
                                                <option value="{{ $q->id }}">{{ $q->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="description" value="{{ old('description') }}" required type="text"
                                            class="form-control" placeholder="Description">
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

    {{-- Program List Ends --}}

@endsection
