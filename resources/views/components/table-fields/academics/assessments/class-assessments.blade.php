@php
    use App\Helpers\Qs;
@endphp

<table class="table table-bordered table-hover table-striped">
    <tbody>
        <td>Assessment Type</td>
        <td>Total</td>
        <td>End date</td>
        @foreach ($row->class_assessments as $assessment)
            <tr>
                <td>{{ $assessment->assessment_type->name }}</td>
                <td>
                    <span class="display-mode"
                        id="display-mode{{ Qs::hash($assessment->id) }}">{{ $assessment->total }}</span>
                    <input type="text" class="edit-mode form-control" id="class{{ Qs::hash($assessment->id) }}"
                        value="{{ $assessment->total }}" style="display: none;"
                        onchange="updateExamResults('{{ Qs::hash($assessment->id) }}')">
                </td>
                <td>

                    <span class="display-mode"
                        id="display-mode-enddate{{ Qs::hash($assessment->id) }}">{{ date('j F Y', strtotime($assessment->end_date)) }}</span>
                    <input autocomplete="off" type="text" class="edit-mode form-control date-pick"
                        id="enddate{{ Qs::hash($assessment->id) }}" value="{{ $assessment->end_date }}"
                        style="display: none;" onchange="updateExamResults('{{ Qs::hash($assessment->id) }}')">
                </td>

            </tr>
        @endforeach

    </tbody>
</table>
