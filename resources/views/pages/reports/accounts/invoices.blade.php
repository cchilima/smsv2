@extends('layouts.master')
@section('page_title', $pageTitle)
@section('content')
    @php
        use App\Helpers\Qs;
        $totalAmount = 0;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Run reports to get a fully customized output for all invoices.</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <div class="row mt-0 mb-4">
                        <div class="col-md-12">
                            <form class="ajax-store-test" method="post" action="{{ route('invoices-results') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6"> From
                                        <input name="from_date" type="text" class="form-control date-pick"
                                            placeholder="Date">
                                    </div>
                                    <div class="col-md-6"> To
                                        <input name="to_date" type="text" class="form-control date-pick"
                                            placeholder="Date">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="text-right">
                                        <button id="ajax-btn" type="submit" class="btn btn-primary">Search <i
                                                class="icon-paperplane"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($datesSet)
                        @livewire('datatables.reports.accounting.revenue.invoices-summary', [
                            'fromDate' => $fromDate,
                            'toDate' => $toDate,
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
