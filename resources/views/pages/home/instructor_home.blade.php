@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp


    @if(true)
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-blue-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ 00 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ 00 }}</h3>
                            <span class="text-uppercase font-size-xs">Total Staff</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success-400 has-bg-image">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-pointer icon-3x opacity-75"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ 00 }}</h3>
                            <span class="text-uppercase font-size-xs">Total Administrators</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-indigo-400 has-bg-image">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-user icon-3x opacity-75"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ 1 }}</h3>
                            <span class="text-uppercase font-size-xs">Total Users</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        @if(count($announcements) > 0)
        @foreach($announcements as $announcement)
        <div class="col-12">
            <div class="alert alert-primary d-flex justify-content-between bg-blue-800" role="alert">
                <h4>{{$announcement->title}}</h4>
                <a href="{{route('announcement.fullview', $announcement->id)}}" target="_Blank" class="bg-dark btn btn-primary">Read Notice</a>
            </div>
        </div>
        @endforeach
        @else
        <h4 class="text-center"> No announcements available.</h4>
        @endif
    </div>
    

@endsection
