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

    @include('templates.pdf.includes.page-header', ['title' => 'Student Invoice'])

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
</body>

</html>
