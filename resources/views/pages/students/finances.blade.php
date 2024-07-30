@extends('layouts.master')
@section('page_title', 'Finances - ' . auth()->user()->first_name . ' ' . auth()->user()->last_name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Financial Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">

                        <li class="nav-item">
                            <a href="#invoices" class="nav-link active" data-toggle="tab">{{ 'Invoices' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#statements" class="nav-link" data-toggle="tab">{{ 'Statements' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#payment-history" class="nav-link" data-toggle="tab">{{ 'Payment History' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="invoices">
                            @foreach ($finances['invoices'] as $key => $invoice)
                                <table class="table table-bordered  mb-3 mb-lg-4">
                                    <thead>
                                        <th>#</th>
                                        <th>Fee type</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <h4 class="d-flex align-items-center justify-content-between">
                                                <span>INV - {{ ++$key }}</span>

                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2 mr-lg-3">
                                                        <form action="{{ route('student.download-invoice', $invoice->id) }}"
                                                            method="get">
                                                            @csrf
                                                            <input type="hidden" class="d-none" name="file-type"
                                                                value="pdf">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="icon-download4 mr-1 lr-lg-2"></i>
                                                                <span>PDF</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </h4>
                                        </tr>
                                        @foreach ($invoice->details as $key => $detail)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $detail->fee->name }}</td>
                                                <td>K {{ $detail->amount }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td><b>Total</b></td>
                                            <td>K {{ $invoice->details->sum('amount') }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            @endforeach

                        </div>

                        <div class="tab-pane fade show" id="statements">
                            @foreach ($finances['invoices'] as $key => $invoice)
                                <table class="table table-bordered  mb-3 mb-lg-4">
                                    <thead>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>

                                    </thead>
                                    <tbody>

                                        <tr>
                                            <h4 class="d-flex align-items-center justify-content-between">
                                                <span>INV - {{ ++$key }}</span>

                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2 mr-lg-3">
                                                        <form
                                                            action="{{ route('student.download-statement', $invoice->id) }}"
                                                            method="get">
                                                            @csrf
                                                            <input type="hidden" class="d-none" name="file-type"
                                                                value="pdf">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="icon-download4 mr-1 lr-lg-2"></i>
                                                                <span>PDF</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </h4>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Opening Balance</b> </td>
                                            <td>K {{ $invoice->details->sum('amount') }}</td>
                                        </tr>
                                        @foreach ($invoice->statements as $key => $statement)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $statement->created_at->format('d F Y') }}</td>
                                                <td>Payment</td>
                                                <td>K {{ $statement->amount }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Closing Balance </b></td>
                                            <td>K
                                                {{ $invoice->details->sum('amount') - $invoice->statements->sum('amount') }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            @endforeach

                            <br>

                            @if ($finances['statementsWithoutInvoice']->sum('amount') > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>

                                    </thead>
                                    <tbody>

                                        <tr>
                                            <h4>Not Invoiced</h4>
                                        </tr>

                                        @foreach ($student->statementsWithoutInvoice as $key => $statement)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $statement->created_at->format('d F Y') }}</td>
                                                <td>Payment</td>
                                                <td>K {{ $statement->amount }}</td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>
                                                - K {{ $student->statementsWithoutInvoice->sum('amount') }}.00
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            @endif

                        </div>

                        <div class="tab-pane fade show" id="payment-history">

                            <table class="table table-bordered">
                                <thead>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </thead>
                                <tbody>
                                    @foreach ($finances['receipts'] as $key => $receipt)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $receipt->created_at->format('d F Y') }}</td>
                                            <td>K {{ $receipt->amount }}</td>
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
@endsection
