@extends('layouts.master')
@section('page_title', 'Edit ')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Prerequisite</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('prerequisites.update', Qs::hash($course->id)) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label for="courses" class="col-lg-3 col-form-label font-weight-semibold">Courses <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select Course" class="form-control select-search" readonly="true" name="course_id" id="courses">
                                    <option selected value="{{$course->id}}">{{$course->code.' '.$course->name}}</option>
{{--                                    @foreach($pcourses as $c)--}}
{{--                                        <option value="{{ $c->id }}">{{ $c->code.' - '.$c->name }}</option>--}}
{{--                                    @endforeach--}}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="course-level" class="col-lg-3 col-form-label font-weight-semibold">Prerequisite Courses <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select Prerequisite" multiple  class="form-control select" name="prerequisite_course_id[]" id="course-level">
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" selected>
                                            {{ $course->code.' '.$course->name }}
                                        </option>
                                    @endforeach
                                    @foreach($pcourses as $c)
                                        <option value="{{ $c->id }}">{{ $c->code.' - '.$c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class Edit Ends--}}

@endsection
