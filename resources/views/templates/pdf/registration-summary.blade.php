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

    @if (count($failedCoursesToInclude) > 0)
        <div class="margin-top">
            <h3 class="v-spacer">Previously Failed Courses</h3>
        </div>

        <div class="margin-top">
            <table class="table">
                <tbody>
                    @foreach ($failedCoursesToInclude as $key => $course)
                        <tr class="items">
                            <td>{{ $course['course_code'] }}</td>
                            <td>{{ $course['course_title'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>

</html>
