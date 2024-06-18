@extends('layouts.master')
@section('page_title', 'Applications Reports')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <a class="" href="{{ route('application.index') }}">
            <div class="card card-body bg-blue-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">K {{ $app_apps }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">All Applicants</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-users icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a class="" href="{{ route('status.applications_reports',['accepted',Qs::hash(1)]) }}">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">K {{ $processed }}</h3>
                            <span class="text-uppercase font-size-xs">Accepted</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-credit-card icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a class="" href="{{ route('status.applications_reports',['rejected',Qs::hash(1)]) }}">
                <div class="card card-body bg-success-400 has-bg-image">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-people icon-3x opacity-75"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ $declined }}</h3>
                            <span class="text-uppercase font-size-xs">Rejected</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-sm-6 col-xl-3">
            <a class="" href="{{ route('status.applications_reports',['pending',Qs::hash(1)]) }}">
                <div class="card card-body bg-indigo-400 has-bg-image">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-user icon-3x opacity-75"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ $pending }}</h3>
                            <span class="text-uppercase font-size-xs">Pending</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>


    <div class="row">

        <div class="col-sm-6 col-xl-3">
            <a class="" href="{{ route('status.applications_reports',['complete',Qs::hash(1)]) }}">
                <div class="card card-body bg-dark has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $completed }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Completed</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </a>

    </div>

    <div class="col-sm-6 col-xl-3">
        <a class="" href="{{ route('status.applications_reports',['incomplete',Qs::hash(1)]) }}">
        <div class="card card-body bg-orange has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $incomplete }}</h3>
                    <span class="text-uppercase font-size-xs">Incomplete</span>
                </div>

                <div class="ml-3 align-self-center">
                    <i class="icon-users2 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a class="" href="{{ route('status.applications_reports',['Male',Qs::hash(2)]) }}">
        <div class="card card-body bg-blue has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-pointer icon-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $boys }}</h3>
                    <span class="text-uppercase font-size-xs">Boys</span>
                </div>
            </div>
        </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a class="" href="{{ route('status.applications_reports',['Female',Qs::hash(2)]) }}">
        <div class="card card-body bg-indigo-800 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-user icon-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $girls }}</h3>
                    <span class="text-uppercase font-size-xs">Girls</span>
                </div>
            </div>
        </div>
        </a>
    </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <a class="" href="{{ route('status.applications_reports',['paid',Qs::hash(3)]) }}">
            <div class="card card-body bg-grey has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $paid }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Paid</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-users4 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a class="" href="{{ route('status.applications_reports',['unpaid',Qs::hash(3)]) }}">
            <div class="card card-body bg-violet has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $not_paid }}</h3>
                        <span class="text-uppercase font-size-xs">Not Paid</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-users2 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-teal has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-pointer icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ 00  }}</h3>
                        <span class="text-uppercase font-size-xs">Total Administrators</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
