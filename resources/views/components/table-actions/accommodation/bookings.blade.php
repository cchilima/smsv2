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
                <a href="{{ route('bookings.edit', $row->id) }}" class="dropdown-item"><i class="icon-pencil"></i>Edit</a>
            @endif
            @if (Qs::userIsSuperAdmin())
                <a id="{{ $row->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i
                        class="icon-trash"></i>
                    Delete</a>
                <form method="post" id="item-delete-{{ $row->id }}"
                    action="{{ route('bookings.destroy', $row->id) }}" class="hidden">
                    @csrf @method('delete')</form>

                <form class="ajax-store" method="post" action="{{ route('confirmation.booking') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $row->id }}">
                    <input type="hidden" name="student_id" value="{{ $row->student_id }}">
                    <div class="text-right">
                        <button id="ajax-btn" type="submit" class="dropdown-item"><i class="icon-paperplane ml-2">
                                Confirm Booking</i></button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
