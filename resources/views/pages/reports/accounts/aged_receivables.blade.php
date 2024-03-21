@extends('layouts.master')
@section('page_title', 'Aged receivables')
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
                            <form class="ajax-store-test" method="post" action="{{ route('aged.receivables.post')  }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-4"> To
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
                    @if(isset($age_analysis))
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>Names</th>
                                <th>Student ID</th>
                                <th>Study Mode</th>
                                <th>Program</th>
                                <th>Level</th>
                                <th>Days Aging</th>
                                <th>Percentage</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($age_analysis as $u)
                                <tr>
                                    <td>{{ $u['name']  }}</td>
                                    <td>{{ $u['student_id'] }}</td>
                                    <td>{{ $u['study_mode'] }}</td>
                                    <td>{{ $u['program'] }}</td>
                                    <td>{{ $u['level'] }}</td>
                                    <td>{{ $u['formatted_days'] }}</td>
                                    <td>{{ $u['payment_percentage'] }}</td>
                                    <td>{{ $u['balance'] }}</td>
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
