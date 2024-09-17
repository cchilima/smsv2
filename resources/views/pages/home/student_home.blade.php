@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="row">
        @if ($registrationStatus)
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    <i class="icon icon-alert mr-2"></i>
                    You are not registered. Clear your balance of K{{ $registrationBalance }} to <a class="alert-link"
                        href="{{ route('registration.index') }}" class="alert-link">register</a>

                </div>
            </div>
        @endif

        @if ($resultsPublicationStatus && $viewResultsBalance > 0)
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    <i class="icon icon-alert mr-2"></i>
                    Results published. Clear your balance of K{{ $viewResultsBalance }} to <a class="alert-link"
                        href="{{ route('student-exam_results') }}" class="alert-link">view results</a>
                </div>
            </div>
        @endif

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

    <div class="row">
        <section class="col-12">
            <div class="row d-flex justify-content-center">
                <div class="col">
                    <div class="card">
                        @php
                            $passportPhotoUrl = !auth()->user()->userPersonalInfo?->passport_photo_path
                                ? asset('images/default-avatar.png')
                                : asset(auth()->user()->userPersonalInfo?->passport_photo_path);
                        @endphp

                        <div class="rounded-top text-white d-flex flex-row cover-container">
                            <div class="ml-4 mt-4" style="height: 150px; width: 150px;">
                                <img src="{{ $passportPhotoUrl }}" alt="User passport photo"
                                    class="h-100 w-100 img-thumbnail mt-4 mb-2 rounded-circle passport-photo">
                            </div>
                            <div class="ml-3" style="margin-top: 100px;">
                                <h2 class="mb-0 font-weight-semibold">
                                    {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h2>
                                <p>{{ auth()->user()->student?->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h4>Financial Stats Overview</h4>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">K{{ number_format($totalFees, 2) }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Fees Total</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-cash4 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">K{{ number_format($totalPayments, 2) }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Payments Total</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-credit-card icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ number_format($paymentPercentage, 2) }}%</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Payment Percentage</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-percent icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">K{{ number_format($paymentBalance, 2) }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Payment Balance</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-pie-chart2 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <h4>Quick Links</h4>
        </div>
        <div class="col-sm-6 col-xl-3 cursor-pointer">
            <a href="{{ route('student.profile') }}" class="d-block link">
                <div class="card px-3 py-2">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-0">Profile</p>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-user icon-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3 cursor-pointer">
            <a href="{{ route('registration.index') }}" class="d-block link">
                <div class="card px-3 py-2">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-0">Registration</p>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-clipboard2 icon-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3 cursor-pointer">
            <a href="{{ route('student.finances') }}" class="d-block link">
                <div class="card px-3 py-2">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-0">Finances</p>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-cash2 icon-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3 cursor-pointer">
            <a href="{{ route('student-exam_results') }}" class="d-block link">
                <div class="card px-3 py-2">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-0">Exam Results</p>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-books icon-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4>Help Section</h4>
        </div>

        <div class="col-sm-6 col-xl-3 cursor-pointer">
            <a href="{{ route('students.help.make-payments') }}" class="d-block link">
                <div class="card px-3 py-2">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-0">How to Make Payments</p>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-question3 icon-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

@endsection

@push('css')
    <style>
        .cover-container {
            height: 175px;
            background-image: linear-gradient(to top right, rgba(13, 29, 105, .75), rgba(1, 4, 28, .5)),
                url({{ $passportPhotoUrl }});
            background-size: auto 175px;
            z-index: 1;
        }

        .cover-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(7.5px);
            z-index: -1;
        }

        .passport-photo {
            aspect-ratio: 1/1;
            object-fit: cover
        }
    </style>
@endpush
