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
                <img class="logo" src="{{ asset('images/logo-v2.png') }}" alt="Logo" height="75">
            </td>
            <td class="w-half">
                <h2>Registration Summary</h2>
                <div class="v-spacer">{{ Qs::getSystemName() }}</div>
            </td>
        </tr>
    </table>

    <hr>

    <div class="margin-top">
        <h2 class="v-spacer">Student Information</h2>
    </div>

    <div class="margin-top">
        <table class="table">
            <tbody>
                @foreach ($studentInfo as $key => $value)
                    <tr class="items">
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="margin-top">
        <h2 class="v-spacer">Admission Information</h2>
    </div>

    <div class="margin-top">
        <table class="table">
            <tbody>
                @foreach ($admissionInfo as $key => $value)
                    <tr class="items">
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="margin-top">
        <h2 class="v-spacer">Registered Courses</h2>
    </div>

    <div class="margin-top">
        <table class="table">
            <tbody>
                @foreach ($courses as $key => $course)
                    <tr class="items">
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->name }}</td>
                    </tr>
                @endforeach
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
