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

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        h4 {
            margin: 0;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .margin-top {
            margin-top: 1.25rem;
        }

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241, 245, 249);
        }

        .v-spacer {
            padding: .5rem 0;
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        table.table {
            font-size: 0.875rem;
            /* border-left: 1px solid rgb(219, 230, 240);
            border-top: none;
            border-bottom: none; */
        }

        table.table thead {
            background-color: rgb(96, 165, 250);
        }

        table.table th {
            color: #ffffff;
            padding: 0.75rem;
        }

        table tr.items {
            background-color: rgb(251, 252, 253);
        }

        table tr.items td {
            padding: 0.75rem;
            border: .25px solid rgb(219, 230, 240);
        }

        table tr th {
            text-align: left;
        }

        hr {
            margin: 35px 0;
            border: none;
            border-bottom: 2px solid rgb(153, 153, 153);
        }

        /* .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        } */
    </style>
</head>

<body>

    <table class="w-full">
        <tr>
            <td class="w-half">
                <img class="logo" src="{{ storage_path('images/logo-v2.png') }}" alt="Logo" height="150">
            </td>
            <td class="w-half">
                <h2>INVOICE</h2>
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
                    <div class="v-spacer"><b>Amount Due: </b>K {{ $invoice->details->sum('amount') }}</div>
                </td>
            </tr>

        </table>
    </div>

    <div class="margin-top">
        <table class="table">
            <thead>
                <tr>
                    <th><b>#</b></th>
                    <th><b>Fee Type</b></th>
                    <th><b>Amount</b></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->details as $key => $detail)
                    <tr class="items">
                        <td>{{ ++$key }}</td>
                        <td>{{ $detail->fee->name ?? 'N/A' }}</td>
                        <td>K {{ $detail->amount }}</td>
                    </tr>
                @endforeach

                <tr class="items">
                    <td></td>
                    <td><b>Total</b></td>
                    <td><b>K {{ $invoice->details->sum('amount') }}</b></td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- <div class="total">
        Total: $129.00 USD
    </div> --}}

    {{-- <div class="footer margin-top">
        <div>Thank you</div>
        <div>&copy; Laravel Daily</div>
    </div> --}}
</body>

</html>
