@extends('layouts.master')
@section('page_title', 'Publishing Results for '.$period->code)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Publish Results</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <a href="{{ route('PublishForAllStudents',['ac'=>$period->id,'type'=>0]) }}"
               class="dropdown-item"><i class="icon-eye"></i> Publish Results for {{$period->code}}</a>
            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Program Name</th>
                    <th>Qualification</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($programs as $program)
                    @if($program['students']>0)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $program['name'] }}</td>
                        <td>{{ $program['qualifications'] }}</td>
                        <td>{{ $program['students'] }}</td>
                        <td>
                            {{ ($program['status'] == 0 ? 'unpublished' : 'published') }}
{{--                            <span class="display-mode" id="display-mode{{ Qs::hash($program->id) }}"></span>--}}
{{--                            <div class="form-check form-switch">--}}
{{--                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">--}}
{{--                            </div>--}}
                        </td>


                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">

{{--                                        <a href="{{ route('getPramResults',['aid'=>Qs::hash($apid),'pid'=>Qs::hash($program['id'])]) }}"--}}
{{--                                           class="dropdown-item">View Results <i class="icon-pencil"></i></a>--}}
                                        @foreach($program['levels'] as $level)
                                            <a href="{{ route('getPramResultsLevelCas',['aid'=>Qs::hash($period->id),'pid'=>Qs::hash($program['id']),'level'=>Qs::hash($level['id'] )]) }}"
                                               class="dropdown-item">View {{ $level['name'] }} Results <i class="icon-pencil"></i></a>
                                        @endforeach

                                    @if($program['status'] == 0)
{{--                                            <form class="ajax-store-publish" method="post" action="{{ route('publishProgramResults')  }}">--}}
{{--                                                @csrf--}}

{{--                                                <input type="hidden" name="programID" value="{{ $program['id'] }}">--}}
{{--                                                <input type="hidden" name="academicPeriodID" value="{{ $apid }}">--}}
{{--                                                <input type="hidden" name="type"  value="1">--}}
{{--                                                --}}

{{--                                                <div class="text-right">--}}
{{--                                                    <button id="ajax-btn" type="submit" class="dropdown-item">Publish Results <i class="icon-paperplane ml-2"></i></button>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                            <a href="{{ route('PublishForAllStudents',['ac'=>$period->id,'type'=>0]) }}"--}}
{{--                                               class="dropdown-item"><i class="icon-eye"></i> Publish Results</a>--}}

                                    @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
