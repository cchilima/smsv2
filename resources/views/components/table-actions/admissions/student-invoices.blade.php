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
                <a href="{{ route('accounting.invoice_details', $row->id) }}" class="dropdown-item"><i
                        class="icon-eye"></i>
                    view</a>
            @endif
        </div>
    </div>
</div>
