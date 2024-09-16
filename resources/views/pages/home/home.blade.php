@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">K {{ $todaysPayments }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Today's Payments</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-coin-dollar icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">K {{ $todaysInvoices }}</h3>
                        <span class="text-uppercase font-size-xs">Today's Invoices</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-credit-card icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-users icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ 00 }}</h3>
                        <span class="text-uppercase font-size-xs">Active students</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-user-check icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ $registered }}</h3>
                        <span class="text-uppercase font-size-xs">Registered Students</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $todaysApplicants }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Today's Applicants</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-user-plus icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $applicants }}</h3>
                        <span class="text-uppercase font-size-xs">Applicants</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-user-plus icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-user-lock icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ $admin }}</h3>
                        <span class="text-uppercase font-size-xs">Administrators</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-users icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ $users }}</h3>
                        <span class="text-uppercase font-size-xs">Users</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $students }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Students</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-users icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $staff }}</h3>
                        <span class="text-uppercase font-size-xs">Staff</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-man-woman icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-teal has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-pointer icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ $admin }}</h3>
                        <span class="text-uppercase font-size-xs">Administrators</span>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-brown has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-user icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ $users }}</h3>
                        <span class="text-uppercase font-size-xs">Users</span>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    @if (true)
        <div class="row">
            @if (count($announcements) > 0)
                <div class="col-12">
                    @foreach ($announcements as $announcement)
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <a class="d-block h-100" href="{{ route('announcement.fullview', $announcement->id) }}">
                                <i class="icon icon-info22 mr-2"></i>
                                <span class="mr-2">{{ str()->limit($announcement->title, 100) }}</span>
                                <span class="alert-link">Read more &rarr;</span>
                                <form action="{{ route('announcement.dismiss', $announcement->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    @endif

@endsection
