<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>

    @include('templates.pdf.includes.pdf-styles')
</head>

<body>

    <div id="main-header">
        <div class="left">
        </div>
        <div class="right">
        </div>
    </div>
    <div class="header" style="text-align:center;">
        <img src="{{ storage_path('images/logo-v2.png') }}" alt=""
            style="
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
                height:90px;
                margin-bottom: 5px;
            ">
        <h1 style="color: black;font-size:20px; font-weight: bold; margin-top: -10px;">
            ZAMBIA UNIVERSITY COLLEGE OF TECHNOLOGY
        </h1>
        <br>
        <h3 style="font-size: 14px;">EXAMINATION ATTENDANCE REGISTER</h3>

    </div>
    <br><br><br>
    <!-- <br> -->
    @foreach ($academics as $academic)
        <div class="">
            <div class="col" style="font-weight: bold;">
                <br><br>
                <!-- <br><br> -->
                <p>ACADEMIC PERIOD: {!! strtoupper($academic['name']) !!}</p>
                <br>
                <p>NUMBER OF STUDENTS: {!! $academic['total_students'] !!}</p>
            </div>
            <br><br>

        </div>
        @foreach ($academic['classes'] as $class)
            <div class="col" style="font-weight: bold;">
                <br><br>
                <!-- <br><br> -->
                <p style="margin-right: 200px;">COURSE CODE: {!! strtoupper($class['class_code']) !!}</p>
                <p>COURSE NAME: {!! strtoupper($class['class_name']) !!}</p>
            </div>
            <br><br>
            <!-- <br><br><br><br><br><br> -->

            <table class="table table-bordered" style="width:100%;border:1px solid black;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">STUDENT
                            ID</th>
                        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">STUDENT
                            NAMES</th>
                        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">PROGRAMME
                        </th>
                        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">LEVEL
                        </th>
                        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">SIGNATURE
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($class['students'] as $student)
                        <tr style="border: 1px solid black;">
                            <th style="font-size: 12px; height: 25px;text-align:center;border: 1px solid grey;">
                                {!! $student['student_id'] !!}</th>
                            <td style="font-size: 12px; text-align:left;border: 1px solid grey;">{!! $student['name'] !!}
                            </td>
                            <td style="font-size: 12px; text-align:left;border: 1px solid grey;">{!! $student['program'] !!}
                            </td>
                            <td style="font-size: 12px; text-align:center;border: 1px solid grey;">
                                {!! $student['level'] !!}</td>
                            <td style="font-size: 12px; text-align:left;border: 1px solid grey;"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
        <div>
            <br><br>
            <p>INVIGILATORS NAME .....................................................................</p>
            <p>SIGNATURE ............................................</p>
            <p>DATE ........................................</p>

            <br><br>

            <p>INVIGILATORS NAME .....................................................................</p>
            <p>SIGNATURE ............................................</p>
            <p>DATE ........................................</p>

        </div>
    @endforeach
</body>

</html>
