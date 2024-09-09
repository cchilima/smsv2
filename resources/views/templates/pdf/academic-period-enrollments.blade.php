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
                <h3 class="top-spacer">Academic Period Enrollments Report</h3>
            </td>
        </tr>
    </table>

    <hr>

    @foreach ($academicPeriods as $academicPeriod)
        <div class="margin-top">
            <h3 class="">
                {{ $academicPeriod['academic_period_name'] }}
            </h3>
        </div>

        @foreach ($academicPeriod['programs'] as $key => $program)
            <div class="margin-top">
                <h4 class="v-spacer">
                    <b>{{ $program['program_name'] }} ({{ $program['program_code'] }})</b>
                </h4>
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
