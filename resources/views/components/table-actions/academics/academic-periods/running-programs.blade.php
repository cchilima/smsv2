@php
    use App\Helpers\Qs;
@endphp

@props(['row', 'academicPeriodId'])

<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">
            @if (Qs::userIsTeamSA())
                <a href="{{ route('student.one.program.list', ['ac' => $academicPeriodId, 'pid' => $row->id]) }}"
                    class="dropdown-item"><i class="icon-paperplane"></i>
                    Download PDF List</a>
                <a href="{{ route('student.csv.one.program.list', ['ac' => $academicPeriodId, 'pid' => $row->id]) }}"
                    class="dropdown-item"><i class="icon-paperplane"></i>
                    Download CSV List</a>
            @endif
        </div>
    </div>
</div>
