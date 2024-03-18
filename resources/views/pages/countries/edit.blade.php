@extends('layouts.master')
@section('page_title', 'Edit Country')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Country</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post"
                        action="{{ route('countries.update', $country->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Country <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $country->country }}" required type="text"
                                    class="form-control" placeholder="Country">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Nationality <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="nationality" value="{{ $country->nationality }}" required type="text"
                                    class="form-control" placeholder="Nationality">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Alpha Code 2 <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="alpha_2_code" value="{{ $country->alpha_2_code }}" required type="text"
                                    class="form-control" placeholder="ZM">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Alpha Code 3 <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="alpha_3_code" value="{{ $country->alpha_3_code }}" required type="text"
                                    class="form-control" placeholder="ZMB">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Dialing Code <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="dialing_code" value="{{ $country->dialing_code }}" required type="text"
                                    class="form-control" placeholder="+260">
                            </div>
                        </div>

                        <div class="text-right">
                            <button id="ajax-btn" type="submit" class="btn btn-primary">Update form <i
                                    class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Country Ends --}}
@endsection
