@php
    use App\Helpers\Qs;
@endphp

<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">

            <a href="#" class="dropdown-item edit-total-link"><i class="icon-pencil"></i> Edit</a>

            @if (Qs::userIsSuperAdmin())
                <a id="{{ Qs::hash($row['class_assessment_id']) }}" onclick="confirmDelete(this.id)" href="#"
                    class="dropdown-item"><i class="icon-trash"></i>
                    Delete</a>
                <form method="post" id="item-delete-{{ Qs::hash($row['class_assessment_id']) }}"
                    action="{{ route('classAssessments.destroy', Qs::hash($row['class_assessment_id'])) }}"
                    class="hidden">@csrf @method('delete')</form>
            @endif

        </div>
    </div>
</div>
