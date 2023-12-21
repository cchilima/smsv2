@extends('layouts.master')
@section('page_title', 'Student Profile - ')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body">
                    <img style="width: 90%; height:90%" src="{{ 00 }}" alt="photo" class="rounded-circle">
                    <br>
                    <h3 class="mt-3">Names</h3>
                </div>
            </div>
            <div class="justify-content-between">
                <button type="button" class="btn btn-primary">
                    Launch static backdrop modal
                </button>

                <button type="button" class="btn btn-primary">
                    Launch static backdrop modal
                </button>

            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-collapsed">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Account</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#account-info" class="nav-link active"
                               data-toggle="tab">{{ 'Account Details' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="account-info">
                            <div class="card card-collapsed">
                                <div class="card-header header-elements-inline">
                                    <h6 class="card-title">Accounting Information</h6>
                                    {!! Qs::getPanelOptions() !!}
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                        <li class="nav-item"><a href="#summary" class="nav-link active">Summary</a></li>
                                        <li class="nav-item"><a href="#quotations" class="nav-link">Quotations</a></li>
                                        <li class="nav-item"><a href="#invoices" class="nav-link">Invoices</a></li>
                                        <li class="nav-item"><a href="#receipts" class="nav-link">Receipts</a></li>
                                        <li class="nav-item"><a href="#credit-notes" class="nav-link">Credit Notes</a>
                                        </li>
                                        <li class="nav-item"><a href="#non-cash-payments" class="nav-link">Non Cash
                                                Payments</a></li>
                                        <li class="nav-item"><a href="#statement" class="nav-link">Statement of
                                                Account</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        {{--Basic Info--}}
                                        <div class="tab-pane fade show active" id="summary">

                                        </div>
                                        <div class="tab-pane fade show" id="quotations">
                                            <table class="table datatable-button-html5-columns">
                                                <thead>
                                                <tr>
                                                    <th>Quotation #</th>
                                                    <th>Names</th>
                                                    <th>Date</th>
                                                    <th>Grand Total</th>
                                                    <th>Operation</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($accounting['quotations'] as $quot)
                                                    <tr>
                                                        <td>{{ $quot['id'] }}</td>
                                                        <td>{{ $quot['names'] }}</td>
                                                        <td>{{ $quot['date'] }}</td>
                                                        <td>{{ $quot['total'] }}</td>
                                                        <td></td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade show" id="invoices">
                                            <table class="table datatable-button-html5-columns">
                                                <thead>
                                                <tr>
                                                    <th>Invoice #</th>
                                                    <th>Academic Period</th>
                                                    <th>Raised By</th>
                                                    <th>Date</th>
                                                    <th>Grand Total</th>
                                                    <th>Operation</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($accounting['invoices'] as $inv)
                                                    <tr>
                                                        <td>{{ $inv['id'] }}</td>
                                                        <td>{{ $inv['academicPeriod'] }}</td>
                                                        <td>{{ $inv['raisedby'] }}</td>
                                                        <td>{{ $inv['date'] }}</td>
                                                        <td>{{ $inv['total'] }}</td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade show" id="receipts">
                                            <table class="table datatable-button-html5-columns">
                                                <thead>
                                                <tr>
                                                    <th>Receipt #</th>
                                                    <th>Payment Method</th>
                                                    <th>Collected By</th>
                                                    <th>Date</th>
                                                    <th>Grand Total</th>
                                                    <th>Operation</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($accounting['receipts'] as $recei)
                                                    <tr>
                                                        <td>{{ $recei['id'] }}</td>
                                                        <td>{{ $recei['payment_method'] }}</td>
                                                        <td>{{ $recei['collectedBy'] }}</td>
                                                        <td>{{ $recei['date'] }}</td>
                                                        <td>ZMW {{ $recei['ammount_paid'] }}</td>
                                                        <td></td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade show" id="credit-notes">
                                            <table class="table datatable-button-html5-columns">
                                                <thead>
                                                <tr>
                                                    <th>Credit Note #</th>
                                                    <th>Invoice No#</th>
                                                    <th>Names</th>
                                                    <th>Student ID</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Issued By</th>
                                                    <th>Authorized By</th>
                                                    <th>operation</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($accounting['credit_notes'] as $cd)
                                                    <tr>
                                                        <td>{{ $cd['id'] }}</td>
                                                        <td>{{ $cd['invoice_id'] }}</td>
                                                        <td>{{ $cd['name'] }}</td>
                                                        <td>{{ $cd['studentid'] }}</td>
                                                        <td>ZMW {{ $cd['total'] }}</td>
                                                        <td>{{ $cd['status'] }}</td>
                                                        <td>{{ $cd['issued_by'] }}</td>
                                                        <td>{{ $cd['authorized_by'] }}</td>
                                                        <td>{{ $cd['status'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade show" id="non-cash-payments">
                                            <table class="table datatable-button-html5-columns">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Invoice No.</th>
                                                    <th>Amount</th>
                                                    <th>Discount</th>
                                                    <th>Comment</th>
                                                    <th>Status</th>
                                                    <th>Raised By</th>
                                                    <th>Processed By</th>
                                                    <th>Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{--                                @foreach($accounting['nonCashPayments'] as $ncp)--}}
                                                {{--                                    <tr>--}}
                                                {{--                                        <td>{{ $ncp['id'] }}</td>--}}
                                                {{--                                        <td>{{ $cd['invoice_id'] }}</td>--}}
                                                {{--                                        <td>{{ $cd['name'] }}</td>--}}
                                                {{--                                        <td>{{ $cd['student_id'] }}</td>--}}
                                                {{--                                        <td>ZMW {{ $cd['total'] }}</td>--}}
                                                {{--                                        <td>{{ $cd['status'] }}</td>--}}
                                                {{--                                        <td>{{ $cd['issued_by'] }}</td>--}}
                                                {{--                                        <td>{{ $cd['authorized_by'] }}</td>--}}
                                                {{--                                        <td>{{ $cd['status'] }}</td>--}}
                                                {{--                                    </tr>--}}
                                                {{--                                @endforeach--}}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade show" id="statement">
                                            <table class="table datatable-button-html5-columns">
                                                <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Reference #</th>
                                                    <th>Description</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                    <th>Balance</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($accounting['statement'] as $stmt)
                                                    <tr>
                                                        <td>{{ $stmt['date'] }}</td>
                                                        <td>{{ $stmt['reference'] }}</td>
                                                        <th>{{ $stmt['description'] }}</th>
                                                        <td>{{ $stmt['debit'] }}</td>
                                                        <td>{{ $stmt['credit'] }}</td>
                                                        <td>ZMW {{ $stmt['balance'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

