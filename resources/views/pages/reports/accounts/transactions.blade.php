@extends('layouts.master')
@section('page_title', 'Transactions')
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
                                    <div class="col-md-4"> From
                                        <input name="from_date" type="text" class="form-control date-pick" placeholder="Date">
                                    </div>
                                    <div class="col-md-4"> To
                                        <input name="to_date" type="text" class="form-control date-pick" placeholder="Date">
                                    </div>
                                    <div class="col-md-4"> Payment Method
                                        <select required
                                                class="select-search form-control" name="payment_method"
                                                id="payment_method">
                                            <option value="">select</option>
                                            @foreach($payment_methods as $p)
                                                <option value="{{$p->id}}">{{$p->name}}</option>
                                            @endforeach
                                        </select>
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
