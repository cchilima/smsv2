<table>
    <thead>
        <tr>
            <th><b>#</b></th>
            <th><b>Fee Type</b></th>
            <th><b>Amount</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $finalTotal = 0;
        @endphp

        @foreach ($invoices as $outerKey => $invoice)
            <tr>
                <td colspan="3"><b>INV - {{ ++$outerKey }}</b></td>
            </tr>

            @foreach ($invoice->details as $innerKey => $detail)
                <tr>
                    <td>{{ ++$innerKey }}</td>
                    <td>{{ $detail->fee->name ?? 'N/A' }}</td>
                    <td>K {{ $detail->amount }}</td>
                </tr>
            @endforeach

            <tr>
                <td></td>
                <td><b>Invoice Total</b></td>
                <td><b>K {{ $invoice->details->sum('amount') }}</b></td>
            </tr>
            <tr colspan="3"></tr>

            {{ $finalTotal += $invoice->details->sum('amount') }}
        @endforeach

        <tr>
            <td></td>
            <td><b>Final Total</b></td>
            <td><b>K {{ $finalTotal }}</b></td>
        </tr>

    </tbody>
</table>
