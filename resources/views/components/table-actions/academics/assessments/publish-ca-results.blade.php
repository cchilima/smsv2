@php
    use App\Helpers\Qs;
@endphp

@props(['entry', 'periodId'])

<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">

            {{--                                        <a href="{{ route('getPramResults',['aid'=>Qs::hash($apid),'pid'=>Qs::hash($program['id'])]) }}" --}}
            {{--                                           class="dropdown-item">View Results <i class="icon-pencil"></i></a> --}}
            @foreach ($entry->levels as $level)
                <a href="{{ route('getPramResultsLevelCas', ['aid' => Qs::hash($periodId), 'pid' => Qs::hash($entry->id), 'level' => Qs::hash($level['id'])]) }}"
                    class="dropdown-item">View {{ $level['name'] }} Results</a>
            @endforeach

            @if ($entry->status == 0)
                {{--                                            <form class="ajax-store-publish" method="post" action="{{ route('publishProgramResults')  }}"> --}}
                {{--                                                @csrf --}}

                {{--                                                <input type="hidden" name="programID" value="{{ $program['id'] }}"> --}}
                {{--                                                <input type="hidden" name="academicPeriodID" value="{{ $apid }}"> --}}
                {{--                                                <input type="hidden" name="type"  value="1"> --}}
                {{--                                                --}}

                {{--                                                <div class="text-right"> --}}
                {{--                                                    <button id="ajax-btn" type="submit" class="dropdown-item">Publish Results <i class="icon-paperplane ml-2"></i></button> --}}
                {{--                                                </div> --}}
                {{--                                            </form> --}}
                {{--                                            <a href="{{ route('PublishForAllStudents',['ac'=>$perId,'type'=>0]) }}" --}}
                {{--                                               class="dropdown-item"><i class="icon-eye"></i> Publish Results</a> --}}
            @endif
        </div>
    </div>
</div>
