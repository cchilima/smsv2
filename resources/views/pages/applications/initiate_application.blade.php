@extends('layouts.master')
@section('page_title', 'Application - Step 1')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Let's begin - Supply NRC or passport. </h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="" data-reload="#page-header" method="post"
                        action="{{ route('application.start_application') }}">
                        @csrf @method('POST')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">NRC </label>
                            <div class="col-lg-9">
                                <input id="nrc" maxlength="11" name="nrc" type="text" class="form-control"
                                    placeholder="NRC">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Passport </label>
                            <div class="col-lg-9">
                                <input name="passport" type="text" class="form-control" placeholder="passport">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i
                                    class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
