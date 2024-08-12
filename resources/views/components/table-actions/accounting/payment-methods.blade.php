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
                <a href="{{ route('payment-methods.edit', $row->id) }}" class="dropdown-item"><i class="icon-pencil"></i>
                    Edit</a>
            @endif
            @if (Qs::userIsSuperAdmin())
                <a id="{{ $row->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i
                        class="icon-trash"></i>
                    Delete</a>
                <form method="post" id="item-delete-{{ $row->id }}"
                    action="{{ route('payment-methods.destroy', $row->id) }}" class="hidden">@csrf @method('delete')
                </form>
            @endif
        </div>
    </div>
</div>
