@extends('layouts.master')
@section('page_title', Auth::user()->first_name.'\'s Results')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            {{--            {!! Qs::getPanelOptions() !!}--}}
        </div>

        <div class="card-body">
            <div class="row justify-content-end">
                <div class="col-md-12">
                    <p>
                        This transcript may not include all courses required for your program completion.
                        Please verify with the Academics Office.
                    </p>
                </div>
            </div>
            <hr/>
        </div>

        <div class="row">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-md-12">
                        @foreach($exams as $results)
                        <table class="table table-hover table-striped-columns">
                            <h5 class="p-2"><strong>{{ $results['name'] }}</strong></h5>
                            <thead>
                            <tr>
                                {{--                                        <th>S/N</th>--}}
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Grade</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results['classes'] as $class)
                                <tr>
                                    <td>{{ $class['course_code'] }}</td>
                                    <td>{{ $class['course_name'] }}</td>
                                    <td>{{ $class['grade'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                            <p class="bg-success p-3 align-bottom">Comment : {{ $results['progression']['comment'] }}</p>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Class List Ends--}}

@endsection
