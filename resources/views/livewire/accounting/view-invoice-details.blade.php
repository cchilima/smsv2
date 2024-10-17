@section('page_title', 'Invoice Details')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Invoice Details</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#invoice-details" class="nav-link active" data-toggle="tab">Invoice Details</a>
            </li>
            <li class="nav-item"><a href="#credit-notes" class="nav-link" data-toggle="tab">Credit Notes</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="invoice-details">
                <div class="mt-4">

                    <div class="">
                        <div class="row">
                            <div class="{{ count($creditNoteItems) > 0 ? 'col-md-8' : 'col' }}">
                                <div class="form-group">
                                    <label class="active" for="credit_note_reason">Credit Note Reason</label>
                                    <textarea placeholder="Enter the reason for raising credit note" id="credit_note_reason"
                                        wire:model.live.debounce.500ms="creditNoteReason" class="form-control" rows="1"></textarea>
                                </div>
                            </div>

                            @if (count($creditNoteItems) > 0)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="d-block">&nbsp</label>
                                        <button class="btn btn-primary" wire:click.prevent="raise()"
                                            wire:confirm="Are you sure you want to create credit note?">Raise Credit
                                            Note
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Fee</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->details as $key => $detail)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $detail->fee->name }}</td>
                                            <td>ZMW {{ $detail->amount }}</td>
                                            <td>{{ $detail->created_at->format('d F Y') }}</td>
                                            <td>
                                                {{-- <p>
                                                    <label>
                                                        <input @if ($creditNoteReason == '') disabled @endif
                                                            wire:model="checkedItems"
                                                            wire:click="addItem({{ $detail->id }},{{ $detail->amount }})"
                                                            type="checkbox" class="filled-in" value="{{ $detail->id }}" />
                                                        <span></span>
                                                    </label>
                                                </p> --}}
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ !$creditNoteReason ? 'First enter a reason for raising credit note' : '' }}">
                                                        <input wire:model="checkedItems"
                                                            wire:click="addItem({{ $detail->id }},{{ $detail->amount }})"
                                                            class="custom-control-input" type="checkbox"
                                                            value="{{ $detail->id }}"
                                                            id="flexCheckDefault-{{ $loop->index }}"
                                                            @disabled(!$creditNoteReason)>
                                                        <label class="custom-control-label"
                                                            for="flexCheckDefault-{{ $loop->index }}">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if (count($creditNoteItems) > 0)

                        <div class="mt-4">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($creditNoteItems as $key => $item)
                                        <tr>
                                            <td>CN{{ ++$key }}</td>
                                            <td>ZMW {{ $item['amount'] }}</td>
                                            <td>{{ $item['reason'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif

                </div>
            </div>

            <div class="tab-pane fade" id="credit-notes">
                <div class="mt-4">
                    @if (count($invoice->approvedCreditNotes) > 0)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Fee</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Issued by</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->ApprovedCreditNotes as $key => $note)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $note->invoiceDetail->fee->name }}</td>
                                        <td>ZMW {{ $note->amount }}</td>
                                        <td>
                                            <p class="truncate">{{ $note->reason }}</p>
                                        </td>
                                        <td>{{ $note->status }}</td>
                                        <td>{{ $note->issuer->first_name }} {{ $note->issuer->last_name }}</td>
                                        <td>{{ $note->created_at->format('d F Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        This invoice has no approved credit notes
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
