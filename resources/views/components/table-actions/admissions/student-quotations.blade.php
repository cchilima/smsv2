@php
    use App\Helpers\Qs;
@endphp

<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">
            @if (Qs::userIsTeamSA() || Qs::getUserType() === 'student')


                    <a href="{{ route('accounting.quotation_details', $row->id) }}" class="dropdown-item"><i
                            class="icon-eye"></i>
                        View</a>
         

             {{--   <a class="dropdown-item" href="{{ route('student.download-invoice', $row->id) }}">
                    <i class="icon-download4"></i> Dowload PDF
                </a> --}}
            @endif
        </div>
    </div>
</div>