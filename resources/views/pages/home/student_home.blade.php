@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp



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
            <h4 class="center-text"> No announcements available.</h4>
        @endif
    </div>




@endsection
