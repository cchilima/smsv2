@extends('layouts.master')
@section('page_title', 'Manage Academic Periods')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Academic Periods</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-periods" class="nav-link active" data-toggle="tab">Manage Academic Periods</a></li>
                <li class="nav-item"><a href="#new-period" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Academic Period</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-periods">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Code</th>
                            <th>Registration Date</th>
                            <th>Late Registration Date</th>
                            <th>Period Type</th>
                            <th>Program Intake</th>
                            <th>Study Mode</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($periods as $period)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $period->code }}</td>
                                <td>{{ $period->registration_date }}</td>
                                <td>{{ $period->late_registration_date }}</td>
                                <td>{{ $period->periodType->name }}</td>
                                <td>{{ $period->programIntake->name }}</td>
                                <td>{{ $period->studyMode->name }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('academic-periods.edit', $period->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $period->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $period->id }}" action="{{ route('academic-periods.destroy', $period->id) }}" class="hidden">@csrf @method('delete')</form>
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

                <div class="tab-pane fade" id="new-period">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('academic-periods.store') }}">
                                @csrf
                                <!-- Add form fields for creating a new academic period -->
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Code <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="code" value="{{ old('code') }}" required type="text" class="form-control" placeholder="Code">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Registration Date <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="registration_date" value="{{ old('registration_date') }}" required type="date" class="form-control" placeholder="Registration Date">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Late Registration Date <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="late_registration_date" value="{{ old('late_registration_date') }}" required type="date" class="form-control" placeholder="Late Registration Date">
                                    </div>
                                </div>

                                <!-- Use loops for dropdowns -->

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Period Type <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="period_type_id" class="form-control" required>
                                            @foreach ($periodTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold"> Intake <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="academic_period_intake_id" class="form-control" required>
                                            @foreach ($intakes as $intake)
                                                <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Study Mode <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="study_mode_id" class="form-control" required>
                                            @foreach ($studyModes as $mode)
                                                <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Academic Period List Ends --}}
@endsection
