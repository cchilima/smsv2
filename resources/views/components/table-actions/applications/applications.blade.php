@php
    use App\Helpers\Qs;
@endphp

<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">
            <a href="{{ route('application.show', $row->id) }}" class="dropdown-item">View</a>

            @if ($row->status === 'incomplete')
                <a href="/application/step-2/{{ $row->id }}" class="dropdown-item">Continue</a>
            @endif

        </div>
    </div>
</div>
