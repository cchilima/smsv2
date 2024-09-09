@extends('layouts.master')
@section('page_title', 'Edit Academic Period Class')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Academic Period Class</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post"
                        action="{{ route('academic-period-classes.update', $periodClass->id) }}">
                        @csrf @method('PUT')

                        <!-- Use loops for dropdowns -->

                        <input type="hidden" name="academic_period_id" value="{{ $periodClass->academicPeriod->id }}">

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Courses <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="course_id" class="form-control" required>
                                    <option value="{{ $periodClass->course->id }}">{{ $periodClass->course->name }} -
                                        {{ $periodClass->course->code }}</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }} - {{ $course->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Instructors <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="instructor_id" class="form-control" required>
                                    <option value="{{ $periodClass->instructor->id }}">
                                        {{ $periodClass->instructor->first_name . ' ' . $periodClass->instructor->last_name }}
                                    </option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}"> {{ $instructor->first_name }}
                                            {{ $instructor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update <i class="icon-pencil ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Academic Period Class Ends --}}
@endsection
