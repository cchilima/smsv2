@section('page_title', 'Quotation Details')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Quotation - {{ $quotation->period->name }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Fee</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($quotation->details as $key => $detail)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $detail->fee->name }}</td>
                        <td>ZMW {{ $detail->amount }}</td>
                        <td>{{ $detail->created_at->format('d F Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
