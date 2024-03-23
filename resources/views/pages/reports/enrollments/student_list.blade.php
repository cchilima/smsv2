@extends('layouts.master')
@section('page_title', 'Student List Report')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Run reports to get a fully customized output for all receipts.</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <div class="row mt-0 mb-1">
                        <div class="col-md-12">
                            <form class="ajax-store-test" method="post" action="{{ route('transaction-results')  }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Academic Period <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="instructor_id" class="form-control select-search" required>
                                                    <option
                                                        value="">select option</option>
                                                    {{--                                                    @foreach ($instructors as $instructor)--}}
                                                    {{--                                                        <option--}}
                                                    {{--                                                            value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name }}</option>--}}
                                                    {{--                                                    @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Programs <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="instructor_id" class="form-control select-search" required>
                                                    <option
                                                        value="">select option</option>
                                                    {{--                                                    @foreach ($instructors as $instructor)--}}
                                                    {{--                                                        <option--}}
                                                    {{--                                                            value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name }}</option>--}}
                                                    {{--                                                    @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="text-right">
                                        <button id="ajax-btn" type="submit" class="btn btn-primary">Search <i class="icon-paperplane"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if(isset($transactions))
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>Receipt ID</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Program</th>
                                <th>Amount</th>
                                <th>Date Created</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($transactions as $u)
                                <tr>
                                    <td>{{ $u->id  }}</td>
                                    <td>{{ $u->student_id }}</td>
                                    <td>{{ $u->student->user->first_name.' '.$u->student->user->last_name }}</td>
                                    <td>{{ $u->student->program->name}}</td>
                                    <td>K{{ $u->amount }}</td>
                                    <td>{{ date('j F Y', strtotime($u->created_at)) }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
