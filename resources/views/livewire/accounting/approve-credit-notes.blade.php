<div>
    <div class="container mt-10">

        <b class="flow-text light-deca">Credit Notes</b>
@if($credit_notes)
        <div class="white z-depth-1 rounded mt-4">
            <table class="responsive-table centered">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Student No.</th>
                        <th>Fee</th>
                        <th>Amount</th>
                        <th>Issued by</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($credit_notes as $key => $note)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $note->invoiceDetail->invoice->student_id }}</td>
                            <td>{{ $note->invoiceDetail->fee->name }}</td>
                            <td>ZMW {{ $note->amount }}</td>
                            <td>{{ $note->issuer->first_name }} {{ $note->issuer->last_name }}</td>
                            <td>{{ $note->created_at->format('d F Y') }}</td>
                            <td>
                                <a href="{{ route('accounting.invoice_details', $note->invoiceDetail->invoice->id) }}"
                                    class="btn btn-small black rounded-lg">view</a>
                                <a wire:click="approve({{$note->id}})" class="btn btn-small black rounded-lg">approve</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @else

        <p>No credit notes pending approval.</p>

        @endif
    </div>



    @script
        <script>

            $wire.on('approved', () => {
                M.toast({
                    html: 'credit note approved successfully'
                })
            });

            $wire.on('approval-failed', () => {
                M.toast({
                    html: 'approval unsuccessful'
                })
            });
            
        </script>
    @endscript

</div>
