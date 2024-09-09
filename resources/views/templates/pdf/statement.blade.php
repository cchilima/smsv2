<?php
use App\Helpers\Qs;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @include('templates.pdf.includes.pdf-styles')
</head>

<body>

    <table class="w-full">
        <tr>
            <td class="w-half">
                <img class="logo" src="{{ asset('images/logo-v2.png') }}" alt="Logo" height="150">
            </td>
            <td class="w-half">
                <h2>STATEMENT</h2>
                <div class="v-spacer">{{ Qs::getSystemName() }}</div>
                <div class="v-spacer">P.O. Box 71601</div>
                <div class="v-spacer">Ndola</div>
                <div class="v-spacer">Zambia</div>
            </td>
        </tr>
    </table>

    <hr>

    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div class="v-spacer">To:</div>
                    <div class="v-spacer"><b>{{ $student->user->first_name . ' ' . $student->user->last_name }}</b>
                    </div>
                    <div class="v-spacer">{{ $student->user->userPersonalInfo->street_main }}</div>
                    <div class="v-spacer">{{ $student->user->userPersonalInfo->town->name }}</div>
                    <div class="v-spacer">
                        {{ $student->user->userPersonalInfo->province->name }},
                        {{ $student->user->userPersonalInfo->country->alpha_2_code }}
                    </div>
                </td>
                <td class="w-half">
                    <div class="v-spacer"><b>Invoice Number: </b>{{ $invoice->id }}</div>
                    <div class="v-spacer"><b>Invoiced at: </b>{{ $invoice->created_at->format('d F Y H:i') }}</div>
                    <div class="v-spacer">
                        <b>Balance: </b>K {{ $invoice->details->sum('amount') - $invoice->statements->sum('amount') }}
                    </div>
                </td>
            </tr>

        </table>
    </div>

    <div class="margin-top">
        <table class="table">
            <thead>
                <tr>
                    <th><b>#</b></th>
                    <th><b>Date</b></th>
                    <th><b>Description</b></th>
                    <th><b>Amount</b></th>
                </tr>
            </thead>
            <tbody>
                <tr class="items">
                    <td></td>
                    <td></td>
                    <td><b>Opening Balance</b> </td>
                    <td>K {{ $invoice->details->sum('amount') }}</td>
                </tr>
                @foreach ($invoice->statements as $key => $statement)
                    <tr class="items">
                        <td>{{ ++$key }}</td>
                        <td>{{ $statement->created_at->format('d F Y') }}</td>
                        <td>Payment</td>
                        <td>K {{ $statement->amount }}</td>
                    </tr>
                @endforeach
                <tr class="items">
                    <td></td>
                    <td></td>
                    <td><b>Closing Balance </b></td>
                    <td>K
                        {{ $invoice->details->sum('amount') - $invoice->statements->sum('amount') }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- <div class="footer margin-top">

        <div class="total">
            Total: $129.00 USD
        </div>

        <div>Payment received. Thank you!</div>
        <div>&copy; ZUT</div>
    </div> --}}
</body>

</html>
