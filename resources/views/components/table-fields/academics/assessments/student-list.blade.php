@php
    use App\Helpers\Qs;
@endphp

@props(['row', 'class_ass'])

<div class="edit-total-link">
    @if (!empty($row->student->grades[0]))
        <input type="hidden" id="gradeid{{ Qs::hash($row->student->id) }}" value="{{ $row->student->grades[0]->id }}">
        <span class="display-mode"
            id="display-mode{{ Qs::hash($row->student->id) }}">{{ $row->student->grades[0]->total }}</span>
        <input type="text" class="edit-mode form-control" id="class{{ Qs::hash($row->student->id) }}"
            value="{{ $row->student->grades[0]->total }}" style="display: none;"
            onchange="EnterResults('{{ Qs::hash($row->student->id) }}','{{ $class_ass->class_assessments[0]->total }}',1)">
    @else
        <input type="hidden" id="gradeid{{ Qs::hash($row->student->id) }}" value="0">
        <span class="display-mode" id="display-mode{{ Qs::hash($row->student->id) }}">NE</span>
        <input type="text" class="edit-mode form-control" id="class{{ Qs::hash($row->student->id) }}" value="0"
            style="display: none;"
            onchange="EnterResults('{{ Qs::hash($row->student->id) }}','{{ $class_ass->class_assessments[0]->total }}',0)">
    @endif
</div>

<script>
    document.addEventListener('livewire:init', function() {

        Livewire.hook('morph.added', () => {
            $('.edit-total-link').on('click', function() {
                var row = $(this).closest('tr');
                row.find('.display-mode').hide();
                row.find('.edit-mode').show();
            });
        })
    });
</script>
