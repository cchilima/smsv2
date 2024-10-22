@section('page_title', 'Credit Notes')

@php
    use App\Helpers\Qs;
@endphp

<div>
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Credit Notes</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @if ($credit_notes && count($credit_notes) > 0)
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Student No.</th>
                            <th>Fee</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Issued by</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($credit_notes as $key => $note)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $note->invoiceDetail->invoice->student_id }}</td>
                                <td>{{ $note->invoiceDetail->fee->name }}</td>
                                <td>{{ number_format($note->amount, 2) }}</td>
                                <td style="max-width: 200px;">
                                    <details>
                                        <summary>Reason</summary>
                                        <p>{{ $note->reason }}</p>
                                    </details>
                                </td>
                                <td>{{ $note->issuer->first_name }} {{ $note->issuer->last_name }}</td>
                                <td>{{ $note->created_at->format('d F Y') }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                <a href="{{ route('accounting.invoice_details', $note->invoiceDetail->invoice->id) }}"
                                                    class="dropdown-item">View Invoice</a>

                                                <a wire:confirm="Are you sure you want to approve this credit note?"
                                                    wire:click="approve({{ $note->id }})"
                                                    class="dropdown-item">Approve</a>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are no credit notes pending approval.</p>
            @endif
        </div>
    </div>

</div>
