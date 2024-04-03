@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="row">
        @if (count($announcements) > 0)
            <div class="col-12">
                @foreach ($announcements as $announcement)
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <a class="d-block h-100" href="{{ route('announcement.fullview', $announcement->id) }}">
                            <i class="icon icon-alert mr-2"></i>
                            <span class="mr-2">{{ str()->limit($announcement->title, 100) }}</span>
                            <span class="alert-link">Read more &rarr;</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-12">
                <h4 class="center-text"> No announcements available.</h4>
            </div>
        @endif
    </div>

@endsection
