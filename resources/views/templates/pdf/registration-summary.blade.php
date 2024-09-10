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

    @include('templates.pdf.includes.page-header', ['title' => 'Student Registration Summary'])

    <hr>

    <div class="margin-top">
        <h3 class="v-spacer">Student Information</h3>
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
        <h3 class="v-spacer">Admission Information</h3>
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
        <h3 class="v-spacer">Registered Courses</h3>
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
</body>

</html>
