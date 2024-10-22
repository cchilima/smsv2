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
            @can('edit academic period')
                <a href="{{ route('academic-periods.edit', $row->id) }}" class="dropdown-item"><i class="icon-pencil"></i>
                    Edit</a>
            @endcan
            @endif

            @if (Qs::userIsTeamSA())
            @can('show academic period')
                <a href="{{ route('academic-periods.show', $row->id) }}" class="dropdown-item"><i class="icon-eye"></i>
                    Show</a>
            @endcan
            @endif

            @if (Qs::userIsTeamSA())
            @can('manage academic period')
                <a href="{{ route('academic-period-management.index', ['ac' => $row->id]) }}" class="dropdown-item"><i
                        class="icon-paperplane"></i> Manage</a>
            @endcan
            @endif
            
            @if (Qs::userIsSuperAdmin())
            @can('delete academic period')
                <a id="{{ $row->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i
                        class="icon-trash"></i>
                    Delete</a>
                <form method="post" id="item-delete-{{ $row->id }}"
                    action="{{ route('academic-periods.destroy', $row->id) }}" class="hidden">@csrf @method('delete')
                </form>
            @endcan
            @endif
        </div>
    </div>
</div>
