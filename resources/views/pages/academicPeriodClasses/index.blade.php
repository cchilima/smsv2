@extends('layouts.master')
@section('page_title', 'Manage Academic Period Classes')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Academic Period Classes</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Academic Period Classes</a></li>
                <li class="nav-item"><a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Academic Period Class</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Academic Period</th>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($periodClasses as $period)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $period->academicPeriod->code }}</td>
                                <td>{{ $period->course->code}}</td>
                                <td>{{ $period->instructor->first_name}}</td>

                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('academic-period-classes.edit', $period->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $period->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $period->id }}" action="{{ route('academic-period-classes.destroy', $period->id) }}" class="hidden">@csrf @method('delete')</form>
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
                            <form class="ajax-store" method="post" action="{{ route('academic-periods.store') }}">
                                @csrf
                               
                        <!-- Use loops for dropdowns -->

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Academic Periods <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="period_type_id" class="form-control" required>
                                        @foreach ($academicPeriods as $period)
                                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                        
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Courses <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="academic_period_intake_id" class="form-control" required>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }} {{ $course->code }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Instructors <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                            <select name="study_mode_id" class="form-control" required>
                                            @foreach ($instructors as $instructor)
                                                <option value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name }}</option>
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
