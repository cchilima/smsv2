<div class="container mt-10">

@php
    use App\Helpers\Qs;
@endphp


    <div>

        <ul class="custom-tabs align-left">
            <li class="{{ $currentSection === 'details' ? 'active' : '' }} custom-tab-item"
                wire:click="setSection('details')">
                <a>Invoice Details</a>
            </li>
            <li class="{{ $currentSection === 'credit' ? 'active' : '' }} custom-tab-item"
                wire:click="setSection('credit')">
                <a>Credit Notes</a>
            </li>
        </ul>

        @if ($currentSection == 'details')
            <div class="mt-4">
                @if (count($creditNoteItems) > 0)
                    <a class="btn btn-small rounded-md primary mb-5 right" wire:click.prevent="raise()"
                        wire:confirm="Are you sure you want to create credit note?"> Raise Credit Note<i
                            class="material-icons right">edit_note</i> </a>
                @endif

                <form action="{{ route('student.download-invoice', $invoice->id) }}" method="get">
                    @csrf
                    <button type="submit" class="btn primary rounded-md">
                        <i class="material-icons right white-text">task</i>
                        PDF
                    </button>
                </form>

                <div class="white z-depth-1 rounded mt-4 mb-10">

                    <div class="p-10">
                        <div class="input-field">
                            <textarea id="credit_note_reason" wire:model.live="creditNoteReason" class="materialize-textarea"></textarea>
                            <label class="active" for="credit_note_reason">Enter credit note reason before marking</label>
                        </div>
                    </div>

                    <table class="responsive-table centered">
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
                                        <p >
                                            <label>
                                                <input @if($creditNoteReason == '') disabled  @endif wire:model="checkedItems"
                                                    wire:click="addItem({{ $detail->id }},{{ $detail->amount }})"
                                                    type="checkbox" class="filled-in" value="{{ $detail->id }}" />
                                                <span></span>
                                            </label>
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(count($creditNoteItems)>0)

                <div class="white z-depth-1 rounded mt-4">


                <table class="table centered">
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
        @else
            <div class="mt-4">
                <b class="flow-text light-deca">Invoice Credit Notes</b>
                <div class="white z-depth-1 rounded mt-4">
                    <table class="responsive-table centered">
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
                                    <td><p class="truncate">{{$note->reason}}</p></td>
                                    <td>{{ $note->status }}</td>
                                    <td>{{ $note->issuer->first_name }} {{ $note->issuer->last_name }}</td>
                                    <td>{{ $note->created_at->format('d F Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>




@script
    <script>
        $wire.on('credit-note-created', () => {
            M.toast({
                html: 'credit note created successfully'
            })
        });

        $wire.on('credit-note-exists', () => {
            M.toast({
                html: 'credit note already exists'
            })
        });

        $wire.on('credit-note-failed', () => {
            M.toast({
                html: 'credit note creation unsuccessful'
            })
        });

        $wire.on('raise-another-invoice', () => {
            M.toast({
                html: 'raise a new and then raise credit note.'
            })
        });

        $wire.on('give-reason', () => {
            M.toast({
                html: 'please specify reason'
            })
        });
    </script>
@endscript

</div>