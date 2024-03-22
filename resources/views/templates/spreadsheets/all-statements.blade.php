<table>
    <thead>
        <tr>
            <th><b>#</b></th>
            <th><b>Date</b></th>
            <th><b>Description</b></th>
            <th><b>Amount</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($invoices as $outerKey => $invoice)
            <tr>
                <td colspan="3"><b>INV - {{ ++$outerKey }}</b></td>
            </tr>

            <tr>
                <td colspan="2"></td>
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
                <td colspan="2"></td>
                <td><b>Closing Balance </b></td>
                <td>K
                    {{ $invoice->details->sum('amount') - $invoice->statements->sum('amount') }}
                </td>
            </tr>
            <tr></tr>
        @endforeach

    </tbody>
</table>
