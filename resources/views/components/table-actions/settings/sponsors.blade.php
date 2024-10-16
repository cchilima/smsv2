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
                <a href="{{ route('sponsors.edit', $row->id) }}" class="dropdown-item"><i class="icon-pencil"></i>
                    Edit</a>
            @endif
            @if (Qs::userIsSuperAdmin())
                <a id="{{ $row->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i
                        class="icon-trash"></i>
                    Delete</a>
                <form @submit="$dispatch('marital-status-deleted')" method="post" id="item-delete-{{ $row->id }}"
                      action="{{ route('sponsors.destroy', $row->id) }}" class="hidden">@csrf @method('delete')
                </form>
            @endif
        </div>
    </div>
</div>
