<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>

    @include('templates.pdf.includes.pdf-styles')
</head>

<body>

    @include('templates.pdf.watermark')
    <div id="main-header">
        <div class="left">
        </div>
        <div class="right">
        </div>
    </div>
    <div class="header" style="  text-align:center;">

        <h1 style="color: black; opacity: 1;">
            ZAMBIA UNIVERSITY COLLEGE OF TECHNOLOGY<br>
            {{ strtoupper($course['academic_period_name']) }} EXAMINATIONS SLIP
        </h1>

    </div>

    <div class="row">
        <table style="width:100%; border: 1px solid gray; padding-bottom: 20px;">
            <tr>
                <td><b>STUDENT ID:</b> {{ $student->id }}</td>
                <td><b>NAME:</b> {{ strtoupper($student->user->first_name) }}
                    {{ strtoupper($student->user->middle_name) }} {{ strtoupper($student->user->last_name) }}</td>
                <td><b>GENDER:</b> {{ strtoupper($student->user->gender) }}</td>
            </tr>
            <tr>
                <td> <b>STUDY MODE:</b> {{ strtoupper($student->study_mode->name) }} </td>
                <td> <b>ACADEMIC PERIOD:</b> {{ strtoupper($course['academic_period_name']) }} </td>
                <td><b>PROGRAMME:</b> {{ strtoupper($student->program->name) }} </td>
                <td><b>DATE GENERATED:</b>{{ NOW() }}</td>
            </tr>
            <tr>

            </tr>
        </table>
    </div>
    <br><br>
    <p>Courses to be examined</p>
    <table class="table" style="width:100%;">
        <thead>
            <tr>
                <th style="font-size: 12px; text-align:left" scope="col">Course Code</th>
                <th style="font-size: 12px; text-align:left" scope="col">Course</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($course['courses'] as $course)
                <tr>
                    <th style="font-size: 12px; text-align:left">{{ $course['course_code'] }}</th>
                    <td style="font-size: 12px; text-align:left">{{ $course['course_name'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <p>Signatories:</p>

    <br> <br>
    <table style="width:60%">
        <tr>
            <th>Student</th>
            <th>Registrar Academics</th>
        </tr>

        <tr>
            <td></td>
            <td></td>
        </tr>
        <br>
        <br>
        <p>I {{ strtoupper($student->user->first_name) }} {{ strtoupper($student->user->middle_name) }}
            {{ strtoupper($student->user->last_name) }}, confirm that the information that has been presented is
            accurate and complete.</p>

        <br>
        <br>
        <hr>

        <ul>
            <h5>Note:</h5>
            <li>Print two copies of this form and take them to registrar academic for date stamp and signing.</li>
            <li>First copy student physical file and Second copy for admitance in examinations rooms</li>
            <li>Your Examination slip and Student Identification Card should be presented to the invigilator before
                writing the exams</li>
            <li>You are expected to be seated in the exam room 30 minutes before start time</li>
        </ul>

        @include('templates.pdf.background')
        <div id="main-header">
            <div class="left">
            </div>
            <div class="right">
            </div>
        </div>

</body>

</html>
