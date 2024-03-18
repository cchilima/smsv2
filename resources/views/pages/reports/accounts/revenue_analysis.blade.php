@extends('layouts.master')
@section('page_title', ' Revenue Analysis Report')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Run reports to get a fully detailed output for all invoices.</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <div class="row mt-0 mb-1">
                        <div class="col-md-12">
                            <form class="ajax-store-test" method="post" action="{{ route('revenue-revenue-result')  }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6"> From
                                        <input name="from_date" type="text" class="form-control date-pick" placeholder="Date">
                                    </div>
                                    <div class="col-md-6"> To
                                        <input name="to_date" type="text" class="form-control date-pick" placeholder="Date">
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
{{--                    @dd(isset($revenue_analysis));--}}
                @if(isset($revenue_analysis))
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Invoice Details</th>
                            <th>Date Created</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($revenue_analysis as $u)
                            <tr>
                                <td>{{ $u->id  }}</td>
                                <td>{{ $u->student_id }}</td>
                                <td>{{ $u->student->user->first_name.' '.$u->student->user->last_name }}</td>
                                <td>{{ $u->student->program->name}}</td>
                                <td>
                                    <table class="table table-bordered table-hover table-striped">
                                        <tbody>
                                        <td>Fee Name</td>
                                        <td>Amount</td>
                                        <td>Type</td>
                                        @foreach($u->details as $d)
{{--                                        @foreach ($u->details->where('fee_id', $u->id) as $d)--}}
                                            <tr>
                                                <td>{{ $d->fee->name }}</td>
                                                <td>{{ $d->amount }}</td>
                                                <td>{{ $d->type }}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </td>
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
