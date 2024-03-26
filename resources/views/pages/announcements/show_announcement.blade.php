@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp



    <div class="row">
        <div class="col-md-10 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center">{{$announcement->title}}</h5>
                    <br><br>
                    <p>
                       {!! nl2br($announcement->description) !!}
                    </p>
                    <br><br>
                    @if($announcement->attachment)
                    <a href="{{ $announcement->attachment }}" target="_blank"> <i class="icon-file-download"></i> View attachment</a></td>
                    @endif
                </div>
            </div>
        </div>
    </div>




@endsection
