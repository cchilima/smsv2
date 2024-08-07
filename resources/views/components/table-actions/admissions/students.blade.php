@php
    use App\Helpers\Qs;
@endphp

<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">
            @if (Qs::userIsTeamSA())
                <a href="{{ route('show.student', $row->id, ['ac' => 1]) }}" class="dropdown-item"><i class="icon-eye"></i>
                    Show</a>
            @endif
            @if (Qs::userIsSuperAdmin())
                <a id="{{ $row->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i
                        class="icon-trash"></i> Delete</a>
                <form method="post" id="item-delete-{{ $row->id }}"
                    action="{{ route('students.destroy', $row->id) }}" class="hidden">@csrf @method('delete')</form>
            @endif

        </div>
    </div>
</div>
