<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>

    <style media="screen">
        * {
            font-size: 13px;
        }

        hr {
            border: 0.1px solid #333;
        }

        table {
            width: 100%;
        }

        .box {
            width: 50%;
            float: left;
        }

        h4 {
            margin-bottom: 0px;
            line-height: 0.5;
        }

        h1 {
            font-size: 32px;
            font-weight: bold;
        }

        .results-box {
            padding-bottom: 150px;
            padding-top: 30px;
        }
    </style>

</head>
<body>
    <div class="content">
        <div class="col-md-12 box4">
            <img src="data:image/gif;base64,{{$logo}}" alt="" style="
                  height: 130px;
                  width: 130px;
                  float: right;
                  position: absolute;
                  background-repeat: no-repeat;
                  background-size: contain;
                  margin-top: -20px;
                  ">
            <h1>Statement of Results</h1>
            <h3 id="function-header" class="text-center">Student Results</h3>

            <hr>
            <img src="data:image/gif;base64,{{$logo}}" alt="" style="
                  height: 600px;
                  position: absolute;
                  width: 600px;
                  margin-left: 10%;
                  margin-top: 150px;
                  opacity: 0.2;
                  margin-bottom: 20px;
                  background-repeat: no-repeat;
                  background-size: contain;
                  ">

            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <h4>Candidate Name:</h4>
                        <p>{{ $student->user->first_name}} {{ $student->user->middle_name }} {{ $student->user->last_name }}
                        </p>

                        <h4>Programme: {{ $student->program->name }}</h4>
{{--                        <p>20{{ Auth::user()->enrollment->year }} - {{ Auth::user()->enrollment->period }} ---}}
{{--                            {{ Auth::user()->enrollment->course->certification->type }} ---}}
{{--                            {{ Auth::user()->enrollment->course->name }}</p>--}}
                    </div>
                    <div class="box">
                        <h4>Student #:</h4>
                        <p>{{ $student->id }}</p>

                    </div>
                    <p>.</p>
                </div>
            </div>
            <hr>
            <!--Invoices tab content-->
            <!-- statement -->
            <div class="tab-pane fade show" id="statements" role="tabpanel" aria-labelledby="statements-tab">

                <div class="bill-tab-lg">

                    <div class="row">
                        <div class="col-md-12 p-3">
                            <h3>Program: {{ $student->program->name }} ({{ $student->program->code }}
                                )</h3>
                            @foreach ($results as $innerIndex => $academicData)
                                <table class="table table-hover table-striped-columns mb-3">
                                    <h5 class="p-2">
                                        <strong>{{ $academicData['academic_period_code'] .' - '.$academicData['academic_period_name'] }}</strong>
                                    </h5>
                                    <h5 class="p-2"><strong>{{ $student->id }}</strong></h5>
                                    <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Mark</th>
                                        <th>Grade</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($academicData['grades'] as $course)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <td>{{ $course['course_code'] }}</td>
                                            <td>{{ $course['course_title'] }}</td>
                                            <td> {{ $course['total']  }}</td>
                                            <td>{{ $course['grade']  }}</td>
                                        </tr>

                                    @endforeach
                                    </tbody>

                                </table>
                                <p class="bg-success p-3 align-bottom">Comment
                                    : {{ $academicData['comments']['comment'] }}</p>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-indent">This transcript contains courses that where registered for and results that have been published by the Examination board</p>
                    <hr>

                </div>
            </div>
        </div>
        <!-- ./ statement ends -->
        <hr>
        <p>KEY TO UNDERSTANDING OF GRADES OF ZAMBIA ICT COLLEGE</p>

        <table class="table paper-table table-hover">
            <tbody>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                    </tr>
                </thead>


                <tr class="user" data-href="/admin/user/">
                    <td>86 – 100%</td>
                    <td>A+ </td>
                    <td>DISTINCTION</td>
                </tr>
                <tr>
                    <td>76 – 85%</td>
                    <td>A</td>
                    <td>DISTINCTION</td>
                </tr>
                <tr>
                    <td>68 – 75%</td>
                    <td>B+ </td>
                    <td>MERITORIOUS</td>
                </tr>
                <tr>
                    <td>62 – 67%</td>
                    <td>B</td>
                    <td>VERY STAISFACTORY</td>
                </tr>
                <tr>
                    <td>56 – 61%</td>
                    <td>C+</td>
                    <td>DEFINITE PASS</td>
                </tr>
                <tr>
                    <td>50 – 55%</td>
                    <td>C</td>
                    <td>BARE PASS</td>
                </tr>
                <tr>
                    <td>40 – 49%</td>
                    <td>D+</td>
                    <td>BARE FAIL</td>
                </tr>
                <tr>
                    <td>0 – 39%</td>
                    <td>D</td>
                    <td>CLEAR FAIL</td>
                </tr>
                <tr>
                    <td></td>
                    <td>P</td>
                    <td>COMPENSATORY PASS</td>
                </tr>
                <tr>
                    <td></td>
                    <td>F</td>
                    <td>FAIL</td>
                </tr>
                <tr>
                    <td></td>
                    <td>DQ </td>
                    <td>DISQUALIFIED</td>
                </tr>
                <tr>
                    <td></td>
                    <td>EX </td>
                    <td>EXEMPTION</td>
                </tr>
                <tr>
                    <td></td>
                    <td>INC </td>
                    <td>INCOMPLETE</td>
                </tr>
                <tr>
                    <td></td>
                    <td>LT </td>
                    <td>LEFT WITHOUT PERMISSION</td>
                </tr>
                <tr>
                    <td></td>
                    <td>NE </td>
                    <td>NO EXAMINATION TAKEN</td>
                </tr>
                <tr>
                    <td></td>
                    <td>WP </td>
                    <td>WITHDREW WITH PERMISSION</td>
                </tr>
            </tbody>
        </table>


    </div>

</body>

</html>
