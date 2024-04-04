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

@endsection
