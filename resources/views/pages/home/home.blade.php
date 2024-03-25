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
                            <span class="text-uppercase font-size-xs font-weight-bold">Today's Payments</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-coin-dollar icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ 00 }}</h3>
                            <span class="text-uppercase font-size-xs">Today's invoices</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-credit-card icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success-400 has-bg-image">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-people icon-3x opacity-75"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ 00 }}</h3>
                            <span class="text-uppercase font-size-xs">Active students</span>
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
                            <span class="text-uppercase font-size-xs">Registered Students</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-dark has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ 00 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Today's Applicants</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-orange has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ 00 }}</h3>
                            <span class="text-uppercase font-size-xs">All Applicants</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-blue has-bg-image">
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
                <div class="card card-body bg-indigo-800 has-bg-image">
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

        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-grey has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $students }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-violet has-bg-image">
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
                <div class="card card-body bg-teal has-bg-image">
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
                <div class="card card-body bg-brown has-bg-image">
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

@endsection
