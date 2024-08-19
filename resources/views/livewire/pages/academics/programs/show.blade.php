@section('page_title', 'Program - ' . $program->code)

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
                <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
                    <li class="nav-item">
                        <a href="#all-courses" class="nav-link active" data-toggle="tab">
                            Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#all-add-courses" class="nav-link" data-toggle="tab">
                            Add Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#add-prerequisite-courses" class="nav-link" data-toggle="tab">
                            Add Prerequisite Courses
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div wire:ignore class="tab-pane fade show active" id="all-courses">
                        <livewire:datatables.academics.program-courses :programId="$programId" />
                    </div>

                    <div wire:ignore class="tab-pane fade" id="all-add-courses">
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
                                        <button wire:click="refreshTable('ProgramCoursesTable')" id="ajax-btn"
                                            type="submit" class="btn btn-primary">Submit form <i
                                                class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div wire:ignore class="tab-pane fade" id="add-prerequisite-courses">
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
                                                class="form-control select-search" name="course_id" id="courses">
                                                <option value=""></option>
                                                @foreach ($pcourses as $c)
                                                    <option value="{{ $c->course->id }}">
                                                        {{ $c->course->code . ' - ' . $c->course->name }}
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
                                                class="form-control select" name="prerequisite_course_id[]"
                                                id="course-level">
                                                @foreach ($pcourses as $c)
                                                    <option value="{{ $c->course->id }}">
                                                        {{ $c->course->code . ' - ' . $c->course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button wire:click="refreshTable('ProgramCoursesTable')" id="ajax-btn"
                                            type="submit" class="btn btn-primary">Submit form <i
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
