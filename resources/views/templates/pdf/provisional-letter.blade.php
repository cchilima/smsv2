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


    </style>
</head>

<body>

    <table class="w-full">
        <tr>
            <td class="w-half">
                <img class="logo" src="{{ storage_path('images/logo-v2.png') }}" alt="Logo" height="150">
            </td>
            <td class="w-half">
                <h2>ZUT</h2>
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
                    <div class="v-spacer"><b>{{ $applicant->first_name . ' ' . $applicant->last_name }}</b>
                    </div>
                    <div class="v-spacer">{{ $applicant->address }}</div>
                    <div class="v-spacer">{{ $applicant->town->name }}</div>
                    <div class="v-spacer">
                        {{ $applicant->province->name }},
                        {{ $applicant->country->alpha_2_code }}
                    </div>
                </td>
            </tr>

        </table>
    </div>

    <div class="margin-top">

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <p>Dear Mr/Ms/Mrs <span class="font-weight-bold">{{$applicant->nrc ? 'NRC : ' : 'Passport : '}} {{$applicant->nrc ? $applicant->nrc : $applicant->passport }}</span>,</p>

                <h5 class="mt-4 mb-3">RE: PROVISIONAL OFFER OF ADMISSION</h5>

                <p>We are pleased to inform you that the Senate of the ZUT has offered you admission to the <span class="font-weight-bold">(Program)</span> to study <span class="font-weight-bold">{{$applicant->program->program}}</span> commencing on <span class="font-weight-bold">(Date)</span>.</p>

                <p>This offer is conditional upon fulfilment of the following:</p>
                
                <ol>
                    <li>Immediate payment of fees upon registration (the University will not accept conditional letters/offers of sponsorship from any organisation except a cash payment or a Bank certified cheque). All payments should be made through ZANACO STUDENTS BILL MUSTER.</li>
                    <li>Production of original certificates or statements of results in support of your qualifications and a National Registration Card/Affidavit for identification.</li>
                    <li>Production of a valid Zambian Study Permit (for foreign candidates).</li>
                    <li>You are required to submit four (4) passport photographs during registration.</li>
                    <li>Fees once paid are not refundable.</li>
                    <li>This offer is only valid for the <span class="font-weight-bold">(Academic Year)</span>.</li>
                </ol>

                <p class="mt-4">Sincerely,</p>
                <p><span class="font-weight-bold"></span><br>Admissions Office<br>ZUT.</p>
            </div>
        </div>
    </div>


    </div>


</body>

</html>
