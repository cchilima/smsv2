@extends('layouts.master')
@section('page_title', 'Publishing Results for '.$period->code)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Results Reports</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body mb-4">
            <div class="container">
                <form method="post" class="mt-1 mb-4" action="{{ route('get_reports_results') }}" >
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="academic" value="{{$period->id}}">
                                <label for="intake_id">Program <span class="text-danger">*</span></label>
                                <select onchange="getLevelAssess(this.value)" data-placeholder="Choose..." required name="programID" id="programID"
                                        class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($programs as $p)
                                        <option value="{{ $p->id }}">{{ $p->code.' - '.$p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="level_idAss">Year of Study: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="level_id" id="level_idAss"
                                        class="select-search form-control level_idAss">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="row">
                    <div class="col-6">
                        <div style="text-align: center;">
                            <canvas id="myChart" style="max-width: 500px;"></canvas>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="text-align: center;">
                            <canvas id="analysisCount" style="max-width: 500px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>


{{--            <div class="card-body mt-5">--}}
{{--                <header>Best performing students in a course</header>--}}
{{--                <div class="container mb-2 mt-4">--}}
{{--                    <form method="post" class="mt-1 mb-4" action="{{ route('students.store') }}" data-fouc>--}}
{{--                        @csrf--}}

{{--                        <div class="row">--}}
{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="intake_id">Program <span class="text-danger">*</span></label>--}}
{{--                                    <select data-placeholder="Choose..." required name="programID" id="programID"--}}
{{--                                            class="select-search form-control">--}}
{{--                                        <option value=""></option>--}}
{{--                                        @foreach($programs as $p)--}}
{{--                                            <option value="{{ $p->id }}">{{ $p->code.' - '.$p->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="level_id">Year of Study: <span class="text-danger">*</span></label>--}}
{{--                                    <select data-placeholder="Choose..." required name="level_id" id="level_idAssP"--}}
{{--                                            class="select-search form-control">--}}
{{--                                        <option value=""></option>--}}
{{--                                        --}}{{--                                        @foreach($studyMode as $p)--}}
{{--                                        --}}{{--                                            --}}{{----}}{{--                                        <option {{ (old('my_parent_id') == Qs::hash($p->id)) ? 'selected' : '' }} value="{{ Qs::hash($p->id) }}">{{ $p->name }}</option>--}}
{{--                                        --}}{{--                                            <option value="{{ $p->id }}">{{ $p->name }}</option>--}}
{{--                                        --}}{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="course">Course: <span class="text-danger">*</span></label>--}}
{{--                                    <select data-placeholder="Choose..." required name="course_id" id="course"--}}
{{--                                            class="select-search form-control">--}}
{{--                                        <option value=""></option>--}}
{{--                                        --}}{{--                                        @foreach($studyMode as $p)--}}
{{--                                        --}}{{--                                            --}}{{----}}{{--                                        <option {{ (old('my_parent_id') == Qs::hash($p->id)) ? 'selected' : '' }} value="{{ Qs::hash($p->id) }}">{{ $p->name }}</option>--}}
{{--                                        --}}{{--                                            <option value="{{ $p->id }}">{{ $p->name }}</option>--}}
{{--                                        --}}{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <div class="text-right mt-4">--}}
{{--                            <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i--}}
{{--                                        class="icon-paperplane ml-2"></i></button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-6">--}}
{{--                            <div style="text-align: center;">--}}
{{--                                <canvas id="BestBasedOnClass" style="max-width: 500px;"></canvas>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
@endsection
