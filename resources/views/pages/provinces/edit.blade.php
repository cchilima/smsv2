@extends('layouts.master')
@section('page_title', 'Edit Province')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Province</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post"
                        action="{{ route('provinces.update', $province->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Province <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $province->name }}" required type="text"
                                    class="form-control" placeholder="Copperbelt">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Country: <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Country" required class="select-search form-control"
                                    name="country_id" id="country_id">
                                    <option disabled selected value=""></option>
                                    @foreach ($countries as $country)
                                        <option {{ $country->id === $province->country_id ? 'selected' : '' }}
                                            value="{{ $country->id }}">{{ $country->country }}</option>
                                    @endforeach
                                </select>

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

    {{-- Edit Province Ends --}}
@endsection
