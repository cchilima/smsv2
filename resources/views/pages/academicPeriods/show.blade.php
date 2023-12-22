@extends('layouts.master')
@section('page_title', 'Academic Period - '.$academicPeriod->code)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body">
                    <img style="width: 90%; height:90%" src="{{ '' }}" alt="photo" class="rounded-circle">
                    <br>
                    <h3 class="mt-3">{{ $academicPeriod->code.' - '.$academicPeriod->name }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">General Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item"><a href="#ac-info" class="nav-link active" data-toggle="tab">Some
                                Information</a></li>
                        <li class="nav-item"><a href="#all-periods" class="nav-link" data-toggle="tab">Information</a>
                        </li>
                        <li class="nav-item"><a href="#all-fees" class="nav-link" data-toggle="tab">Fees</a></li>
                    </ul>
                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="ac-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Name</td>
                                    <td>{{ $academicPeriod->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Code</td>
                                    <td>{{ $academicPeriod->code }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Description</td>
                                    <td>{{ $academicPeriod->description }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Description</td>
                                    <td>{{ $academicPeriod->ec_start_date }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Description</td>
                                    <td>{{ $academicPeriod->ac_end_ate }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Description</td>
                                    <td>{{ $academicPeriod->description }}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="all-periods">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                @if(!empty($periods->study_mode->name))
                                    <tr>
                                        <td>Allowed Study Mode</td>
                                        <td>{{ $periods->study_mode->name }}</td>
                                    </tr>
                                @endif
                                @if(!empty($periods->intake->name))
                                    <tr>
                                        <td>Allowed Intake</td>
                                        <td>{{ $periods->intake->name }}</td>
                                    </tr>
                                @endif
                                @if(!empty($periods->view_results_threshold))
                                    <tr>
                                        <td>Results Threshold %</td>
                                        <td>{{ $periods->view_results_threshold }}</td>
                                    </tr>
                                @endif
                                @if(!empty($periods->exam_slip_threshold))
                                    <tr>
                                        <td>Download Exam Slip Threshold %</td>
                                        <td>{{ $periods->exam_slip_threshold }}</td>
                                    </tr>
                                @endif
                                @if(!empty($periods->registration_threshold))
                                    <tr>
                                        <td>Registration Threshold %</td>
                                        <td>{{ $periods->registration_threshold }}</td>
                                    </tr>
                                @endif
                                @if(!empty($periods->registration_date))
                                    <tr>
                                        <td>Registration Open Date</td>
                                        <td>{{ date('j F Y', strtotime($periods->registration_date)) }}</td>
                                    </tr>
                                @endif
                                @if(!empty($periods->late_registration_date))
                                    <tr>
                                        <td>Late Registration Date</td>
                                        <td>{{ date('j F Y', strtotime($periods->late_registration_date)) }}</td>
                                    </tr>
                                @endif
                                @if(!empty($periods->late_registration_end_date))
                                    <tr>
                                        <td>End of Late Registration Date</td>
                                        <td>{{ date('j F Y', strtotime($periods->late_registration_end_date)) }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($periods->id))
                                <div class="text-right">
                                    <a href="{{ route('academic-period-management.edit', Qs::hash($periods->id)) }}"
                                       id="ajax-btn" type="button" class="mbt-3 mt-4 btn btn-primary">Edit Information
                                        <i class="icon-paperplane ml-2"></i></a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="all-fees">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                @foreach($feeInformation as $fee)
                                    <tr>
                                        <td>{{ $fee->fee->name }}</td>
                                        <td>{{ $fee->amount }}</td>
                                        <td><a href="{{ route('academic-period-fees.edit', Qs::hash($fee->id)) }}"
                                               class="dropdown-item"><i class="icon-pencil"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Academic Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body collapse">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            {{--                                    <a href="#all-{{ $level->id }}" class="nav-link{{ $loop->first ? ' active' : '' }}" data-toggle="tab">--}}
                            {{--                                        --}}
                            {{--                                    </a>--}}
                        </li>

                        <li class="nav-item">
                            <a href="#all-add-courses" class="nav-link" data-toggle="tab">
                                Available Classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#all-add-prerequisite-courses" class="nav-link" data-toggle="tab">
                                Running programs
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade" id="all-">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Prerequisite</th> <!-- New column for prerequisites -->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        {{--                                                    @if (count($course['prerequisites']) > 0)--}}
                                        {{--                                                            @foreach ($course['prerequisites'] as $prerequisite)--}}
                                        {{--                                                                {{ $prerequisite['prerequisite_code'].' '.$prerequisite['prerequisite_name'] }}--}}
                                        {{--                                                            @endforeach--}}
                                        {{--                                                    @else--}}

                                        {{--                                                    @endif--}}
                                    </td>
                                    <td>
                                        @if (Qs::userIsSuperAdmin())
                                            {{--                                                        <a id="{{ $course->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>--}}
                                            {{--                                                        <form method="post" id="item-delete-{{ $course->id }}" action="{{ route('program-course.destroy', ['programID' => $myprogram['program']->id, 'levelID' => $level['level'], 'courseID' => $course['course_id']]) }}" class="hidden">@csrf @method('delete')</form>--}}
                                        @endif
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="all-add-courses">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="ajax-store" method="post"
                                          action="{{ route('program-courses.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="courses" class="col-lg-3 col-form-label font-weight-semibold">Courses
                                                <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Course" multiple
                                                        class="form-control select-search" name="course_id[]"
                                                        id="courses">
                                                    <option value=""></option>
                                                    {{--                                                        @foreach($newcourses as $c)--}}
                                                    {{--                                                            <option value="{{ $c->id }}">{{ $c->code.' - '.$c->name }}</option>--}}
                                                    {{--                                                        @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="program_id" value="">

                                        <div class="form-group row">
                                            <label for="course-level"
                                                   class="col-lg-3 col-form-label font-weight-semibold">Level <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Class Type"
                                                        class="form-control select" name="level_id" id="course-level">
                                                    {{--                                                        @foreach($levels as $l)--}}
                                                    {{--                                                            <option value="{{ $l->id }}">{{ $l->name }}</option>--}}
                                                    {{--                                                        @endforeach--}}
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
                        <div class="tab-pane fade" id="all-add-prerequisite-courses">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="ajax-store" method="post" action="{{ route('prerequisites.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="courses" class="col-lg-3 col-form-label font-weight-semibold">Courses
                                                <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Course"
                                                        class="form-control select-search" name="courseID" id="courses">
                                                    <option value=""></option>
                                                    {{--                                                        @foreach($pcourses as $c)--}}
                                                    {{--                                                            <option value="{{ $c->id }}">{{ $c->code.' - '.$c->name }}</option>--}}
                                                    {{--                                                        @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="course-level"
                                                   class="col-lg-3 col-form-label font-weight-semibold">Prerequisite
                                                Courses <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Prerequisite" multiple
                                                        class="form-control select" name="prerequisiteID[]"
                                                        id="course-level">
                                                    {{--                                                        @foreach($pcourses as $c)--}}
                                                    {{--                                                            <option value="{{ $c->id }}">{{ $c->code.' - '.$c->name }}</option>--}}
                                                    {{--                                                        @endforeach--}}
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
        </div>
    </div>
@endsection
