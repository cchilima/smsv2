@php
    use App\Helpers\Qs;
@endphp

<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-left">
            @if (count($row->class_assessments) > 0)
                @foreach ($row->class_assessments as $assess)
                    <a href="{{ route('myClassStudentList', ['class' => Qs::hash($row->id), 'assessid' => Qs::hash($assess->assessment_type->id)]) }}"
                        class="dropdown-item">Enter
                        {{ $assess->assessment_type->name }} Results</a>
                @endforeach
            @endif
            <a href="{{ route('classAssessments.index') }}" class="dropdown-item">Assign
                Assessment</a>
        </div>
    </div>
</div>
