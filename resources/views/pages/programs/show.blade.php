@extends('layouts.master')
@section('page_title', 'Program - ' . $program->code)
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
                    <h3 class="mt-3">{{ $program->name }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">{{ $program->name }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        <div class="tab-pane fade show active" id="basic-info">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Name</td>
                                        <td>{{ $program->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Code</td>
                                        <td>{{ $program->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Description</td>
                                        <td>{{ $program->description }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Courses</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#all-courses" class="nav-link active" data-toggle="tab">
                                Courses
                            </a>
                        </li>
                        @foreach ($withCourseLevels['course_levels'] as $programCourse)
                            <li class="nav-item">
                                <a href="#all-{{ $programCourse['id'] }}" class="nav-link" data-toggle="tab">
                                    {{ $programCourse['name'] }}
                                </a>
                            </li>
                        @endforeach
                        <li class="nav-item">
                            <a href="#all-add-courses" class="nav-link" data-toggle="tab">
                                Add Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#all-add-prerequisite-courses" class="nav-link" data-toggle="tab">
                                Add Prerequisite Courses
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all-courses">
                            <livewire:datatables.academics.program-courses :programId="$programId" />
                        </div>

                        @foreach ($withCourseLevels['course_levels'] as $programCourse)
                            <div class="tab-pane fade" id="all-{{ $programCourse['id'] }}">
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
                                        @foreach ($programCourse['courses'] as $course)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $course['name'] }}</td>
                                                <td>{{ $course['code'] }}</td>
                                                <td>
                                                    {{--                                                    @if (count($course['prerequisites']) > 0) --}}
                                                    {{--                                                            @foreach ($course['prerequisites'] as $prerequisite) --}}
                                                    {{--                                                                {{ $prerequisite['prerequisite_code'].' '.$prerequisite['prerequisite_name'] }} --}}
                                                    {{--                                                            @endforeach --}}
                                                    {{--                                                    @else --}}

                                                    {{--                                                    @endif --}}
                                                </td>
                                                <td>
                                                    {{--                                                    @if (Qs::userIsSuperAdmin()) --}}
                                                    {{--                                                        <a id="{{ $course['id'] }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a> --}}
                                                    {{--                                                        <form method="post" id="item-delete-{{ $course['id'] }}" action="{{ route('program-course.destroy', ['programID' => $withCourseLevels['program_id']->id, 'levelID' => $programCourse['id'], 'courseID' => $course['id']) }}" class="hidden">@csrf @method('delete')</form> --}}
                                                    {{--                                                    @endif --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach

                        <div class="tab-pane fade" id="all-add-courses">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="ajax-store" method="post" action="{{ route('program-courses.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="courses"
                                                class="col-lg-3 col-form-label font-weight-semibold">Courses <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Course" multiple
                                                    class="form-control select-search" name="course_id[]" id="courses">
                                                    <option value=""></option>
                                                    @foreach ($newcourses as $c)
                                                        <option value="{{ $c->id }}">
                                                            {{ $c->code . ' - ' . $c->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="program_id" value="{{ $program->id }}">

                                        <div class="form-group row">
                                            <label for="course-level"
                                                class="col-lg-3 col-form-label font-weight-semibold">Level <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Class Type"
                                                    class="form-control select" name="level_id" id="course-level">
                                                    @foreach ($levels as $l)
                                                        <option value="{{ $l->id }}">{{ $l->name }}</option>
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
                        <div class="tab-pane fade" id="all-add-prerequisite-courses">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="ajax-store" method="post" action="{{ route('prerequisites.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="courses"
                                                class="col-lg-3 col-form-label font-weight-semibold">Courses <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Course"
                                                    class="form-control select-search" name="courseID" id="courses">
                                                    <option value=""></option>
                                                    @foreach ($pcourses as $c)
                                                        <option value="{{ $c->id }}">
                                                            {{ $c->code . ' - ' . $c->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="course-level"
                                                class="col-lg-3 col-form-label font-weight-semibold">Prerequisite Courses
                                                <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select required data-placeholder="Select Prerequisite" multiple
                                                    class="form-control select" name="prerequisiteID[]"
                                                    id="course-level">
                                                    @foreach ($pcourses as $c)
                                                        <option value="{{ $c->id }}">
                                                            {{ $c->code . ' - ' . $c->name }}
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
        </div>
    </div>
@endsection
