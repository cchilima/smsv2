<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>

    <style media="screen">
        #logo {

            height: 200px;
            width: auto;

        }
        h1 {
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 30px;
        }
        body {
            opacity: 1;
        }
        .header {
            padding-left: 15%;
            padding-right: 15%;
        }
        #qrcode {
            bottom: 0;
            position: absolute;
        }
        #main-header {

        }
        #main-header .left{
            width: 20%;
            height: 300px;
            display: inline;
            background-color: blue;
        }
        #main-header .right {
            width: 80%;
            background-color: red;
            display: inline;
            height: 300px;
        }
        #watermark {
            margin-top: -9%;
            z-index: 1;
            position: absolute;
            opacity: 0.1;
            margin-left: 8.5%;
        }
        #background {
            margin-top: -80%;
            z-index: -99;
            margin-left: -10%;
            position: absolute;
            background-repeat: repeat;
            opacity: 0.01;
            height: 1900px;

        }
        p {
            font-size: 12px;
            display: inline;
        }
        td {
            font-size: 10px;
        }
        ul li {
            font-size: 12px;

        }
        hr {
            color: #333;
            border-width: 1px;
        }


    </style>
</head>
<body>


<div id="main-header">
    <div class="left">
    </div>
    <div class="right">
    </div>
</div>
<div class="header" style="text-align:center;">
    <img src="{{ storage_path('images/logo-v2.png') }}" alt="" style="
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
        <p style="margin-right: 200px;">COURSE CODE: {!! strtoupper($class['class_code']) !!}</p> <p>COURSE NAME: {!! strtoupper($class['class_name']) !!}</p>
    </div>
<br><br>
<!-- <br><br><br><br><br><br> -->

<table class="table table-bordered" style="width:100%;border:1px solid black;border-collapse:collapse;">
    <thead>
    <tr>
        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">STUDENT ID</th>
        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">STUDENT NAMES</th>
        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">PROGRAMME</th>
        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">LEVEL</th>
        <th style="font-size: 12px; text-align:center;border: 1px solid black;" scope="col">SIGNATURE</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($class['students'] as $student)

        <tr style="border: 1px solid black;">
            <th style="font-size: 12px; height: 25px;text-align:center;border: 1px solid grey;">{!! $student['student_id'] !!}</th>
            <td style="font-size: 12px; text-align:left;border: 1px solid grey;">{!! $student['name'] !!}</td>
            <td style="font-size: 12px; text-align:left;border: 1px solid grey;">{!! $student['program'] !!}</td>
            <td style="font-size: 12px; text-align:center;border: 1px solid grey;">{!! $student['level']!!}</td>
            <td style="font-size: 12px; text-align:left;border: 1px solid grey;"></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endforeach
<div>
    <br><br>
    <p>INVIGILATORS NAME .....................................................................</p>
    <p>SIGNATURE         ............................................</p>
    <p>DATE              ........................................</p>

    <br><br>

    <p>INVIGILATORS NAME .....................................................................</p>
    <p>SIGNATURE         ............................................</p>
    <p>DATE              ........................................</p>

</div>
@endforeach
</body>
</html>
