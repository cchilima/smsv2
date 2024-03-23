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
<div class="header" style="text-align:center;">
    <img class="logo" src="{{ asset('images/logo-v2.png') }}" alt="" style="
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      height:90px;
      margin-bottom: 5px;
      ">
    <h1 style="color: black;font-size:20px; font-weight: bold; margin-top: -10px;">
        {{ Qs::getSystemName() }}
    </h1>
    <br>
    <h3 style="font-size: 14px;">STUDENT LIST FOR {{ $academic['name'] }} ACADEMIC PERIOD</h3>

</div>

<hr>

<div class="margin-top">

</div>
<div class="w-full">
    <h4>Academic Period : {{ $academic['name'] }}</h4>
    <div class="margin-top">
        <div class="">
            <div class="col" style="font-weight: bold;">
                <br><br>
                <!-- <br><br> -->
                <p>Class CODE: {!! strtoupper( $academic['class_code'] ) !!}</p>
                <br>
                <p>Class NAME: {!! strtoupper( $academic['class_name'] ) !!}</p>
                <br>
                <p>NUMBER OF STUDENTS: {!! count($academic['students']) !!}</p>
            </div>
            <br><br>
        </div>
        <br><br>

        <table class="table table-bordered" style="width:100%;border:1px solid black;border-collapse:collapse;">
            <thead>
            <tr>
                <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">STUDENT ID</th>
                <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">STUDENT NAMES</th>
                <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">EMAIL</th>
                <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">GENDER</th>
                <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">LEVEL</th>
                <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">PERCENTAGE</th>
                <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">BALANCE</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($academic['class']['students'] as $student)

                <tr style="border: 1px solid black;">
                    <th style="font-size: 12px; height: 25px;text-align:center;border: 1px solid grey;">{{ $student['student_id'] }}</th>
                    <td style="font-size: 12px; text-align:left;border: 1px solid grey;">{!! $student['name'] !!} </td>
                    <td style="font-size: 12px; text-align:center;border: 1px solid grey;">{!! $student['email']!!}</td>
                    <td style="font-size: 12px; text-align:center;border: 1px solid grey;">{!! $student['gender']!!}</td>
                    <td style="font-size: 12px; text-align:center;border: 1px solid grey;">{!! $student['level']!!}</td>
                    <td style="font-size: 12px; text-align:center;border: 1px solid grey;">{!! $student['payment_percentage']!!}</td>
                    <td style="font-size: 12px; text-align:center;border: 1px solid grey;">K {!! $student['balance']!!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

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
