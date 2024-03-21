@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="alert alert-primary d-flex justify-content-between bg-blue-800" role="alert">
                <h4>NOTICE: GRADUATION LIST 2023</h4>
                <a href="#" target="_Blank" class="bg-dark btn btn-primary">Read Notice</a>
            </div>
        </div>
    </div>




@endsection
