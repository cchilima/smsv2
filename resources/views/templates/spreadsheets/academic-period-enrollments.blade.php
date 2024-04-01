@foreach ($academicPeriods as $academicPeriod)
    <table>
        <tr>
            <td colspan="5" align="middle">
                <b>{{ $academicPeriod['academic_period_name'] }}</b>
            </td>
        </tr>
    </table>

    <table>

        @foreach ($academicPeriod['programs'] as $key => $program)
            <tr colspan="5">
                <td>
                    <b>{{ $program['program_name'] }} ({{ $program['program_code'] }})</b>
                </td>
            </tr>

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
                <br>

                @if (count($program['students']) > 0)
                    @foreach ($program['students'] as $student)
                        <tr>
                            <td>{{ $student['student_id'] }}</td>
                            <td>{{ $student['name'] }}</td>
                            <td>{{ $student['gender'] }}</td>
                            <td>{{ number_format($student['payment_percentage'], 2) }}</td>
                            <td>{{ $student['balance'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td rowspan="2" colspan="5">No students</td>
                    </tr>
                @endif
                <tr colspan="5"></tr>
            </tbody>
        @endforeach
    </table>
@endforeach
