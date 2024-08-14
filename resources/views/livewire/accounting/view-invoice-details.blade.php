@php
    use App\Helpers\Qs;
@endphp

<div class="container mt-10">
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
                        wire:confirm="Are you sure you want to create credit note?"> raise credit note<i
                            class="material-icons right">edit_note</i> </a>

                    <a>
                @endif

                <form action="{{ route('student.download-invoice', $invoice->id) }}" method="get">
                    @csrf
                    <button type="submit" class="btn primary rounded-md">
                        <i class="material-icons right white-text">task</i>
                        PDF
                    </button>
                </form>
                </a>




                <div class="white z-depth-1 rounded mt-4">


                    <table class="table centered">
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
                                        <p wire:ignore>
                                            <label>
                                                <input wire:model="checkedItems"
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

            </div>
        @else
            <div class="mt-4">


                <b class="flow-text light-deca">invoice credit notes</b>


                <div class="white z-depth-1 rounded mt-4">

                    <table class="table centered">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Fee</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Issued by</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->creditNotes as $key => $note)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $note->invoiceDetail->fee->name }}</td>
                                    <td>{{ $note->amount }}</td>
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
    </script>
@endscript
