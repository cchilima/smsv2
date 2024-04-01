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

        .margin-bottom {
            margin-bottom: 1.25rem;
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
                <img class="logo" src="{{ asset('images/logo-v2.png') }}" alt="Logo" height="75">
            </td>
            <td class="w-half">
                <h2>Academic Period Enrollments</h2>
                <div class="v-spacer">{{ Qs::getSystemName() }}</div>
            </td>
        </tr>
    </table>

    <hr>

    @foreach ($academicPeriods as $academicPeriod)
        <div class="margin-top">
            <h2 class="">
                {{ $academicPeriod['academic_period_name'] }}
            </h2>
        </div>

        @foreach ($academicPeriod['programs'] as $key => $program)
            <div class="margin-top">
                <h3 class="v-spacer">
                    <b>{{ $program['program_name'] }} ({{ $program['program_code'] }})</b>
                </h3>
            </div>

            <div class="margin-top">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Payment %</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($program['students']) > 0)
                            @foreach ($program['students'] as $student)
                                <tr class="items">
                                    <td>{{ $student['student_id'] }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>{{ $student['gender'] }}</td>
                                    <td>{{ number_format($student['payment_percentage'], 2) }}</td>
                                    <td>{{ $student['balance'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="items">
                                <td colspan="5">No students</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <br><br>
        @endforeach
        <br><br>
    @endforeach

</body>

</html>
