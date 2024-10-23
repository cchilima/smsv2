@php
    use App\Helpers\Qs;
@endphp

@can('academic_period.fees - edit')
    <div class="list-icons">
        <div class="dropdown">
            <a href="#" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <a href="{{ route('academic-period-fees.edit', Qs::hash($row->id)) }}" class="dropdown-item"><i
                        class="icon-pencil"></i>Edit</a>
                @if ($row->status == 0)
                    <a href="{{ route('academic-period-fees.edit', Qs::hash($row->id)) }}" class="dropdown-item"><i
                            class="icon-eye"></i>Edit</a>
                @endif
            </div>
        </div>
    </div>
@endcan
