@extends('layouts.master')
@section('page_title', 'Student Profile - '.\Illuminate\Support\Facades\Auth::user()->first_name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body">
                    <img style="width: 90%; height:90%" src="{{ 00 }}" alt="photo" class="rounded-circle">
                    <br>
                    <h3 class="mt-3">{{Auth::user()->first_name.' '.Auth::user()->middle_name.' '.Auth::user()->last_name}} </h3>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Enrollments Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        @foreach ($enrollments as $innerIndex =>$academicData)
                            <li class="nav-item">
                                <a href="#account-{{ $academicData['academic_period_id'] }}" class="nav-link"
                                   data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        @foreach ($enrollments as $innerIndex =>$academicData)
                            <div class="tab-pane fade show" id="account-{{ $academicData['academic_period_id'] }}">

                                <table class="table table-hover table-striped-columns mb-3">
                                    <h5 class="p-2"> Code :
                                        <strong>{{ $academicData['academic_period_code'] }}</strong>
                                    </h5>
                                    <h5 class="p-2">Name : <strong>{{ $academicData['academic_period_name'] }}</strong></h5>
                                    <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($academicData['courses'] as $course)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <td>{{ $course['course_code'] }}</td>
                                            <td>{{ $course['course_title'] }}</td>
                                        </tr>

                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
