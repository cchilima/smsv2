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
            <td class="w-full text-center">
                <img class="logo" src="{{ asset('images/logo-v2.png') }}" alt="Logo" height="65">
                <h2>{{ Qs::getSystemName() }}</h2>
                <span class="v-spacer">{{ Qs::getSetting('po_box') }},</span>
                <span class="v-spacer">{{ Qs::getSetting('address') }},</span>
                <span class="v-spacer">{{ Qs::getSetting('town') }},</span>
                <span class="v-spacer">{{ Qs::getSetting('country') }}.</span>
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
                        {{ $applicant->country->country }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="margin-top">

        <div class="container mt-5">
            <div class="card">
                <div class="card-body">
                    <p>Dear Mr/Ms/Mrs <span class="font-bold">{{ $applicant->nrc ? 'NRC : ' : 'Passport : ' }}
                            {{ $applicant->nrc ? $applicant->nrc : $applicant->passport }}</span>,</p>

                    <h5 class="mt-4 mb-3">RE: PROVISIONAL OFFER OF ADMISSION</h5>

                    <p>We are pleased to inform you that the Senate of the {{ Qs::getSystemName() }} has offered you
                        admission to the <span class="font-bold">(Program)</span> to study <span
                            class="font-bold">{{ $applicant->program->name }}</span> commencing on <span
                            class="font-bold">(Date)</span>.</p>

                    <p>This offer is conditional upon fulfilment of the following:</p>

                    <ol>
                        <li>Immediate payment of fees upon registration (the University will not accept conditional
                            letters/offers of sponsorship from any organisation except a cash payment or a Bank
                            certified cheque). All payments should be made through ZANACO STUDENTS BILL MUSTER.</li>
                        <li>Production of original certificates or statements of results in support of your
                            qualifications and a National Registration Card/Affidavit for identification.</li>
                        <li>Production of a valid Zambian Study Permit (for foreign candidates).</li>
                        <li>You are required to submit four (4) passport photographs during registration.</li>
                        <li>Fees once paid are not refundable.</li>
                        <li>This offer is only valid for the <span class="font-bold">(Academic Year)</span>.</li>
                    </ol>

                    <p class="mt-4">Sincerely,</p>
                    <p><span class="font-bold"></span><br>Admissions Office<br>{{ Qs::getSystemName() }}.</p>
                </div>
            </div>
        </div>

    </div>

</body>

</html>
