@extends('layouts.master')
@section('page_title', 'Edit Setting')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Setting</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post"
                        action="{{ route('settings.update', $setting->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Type <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="type" required type="text" class="form-control"
                                    placeholder="Setting type" value="{{ $setting->type }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="description" required type="text" class="form-control"
                                    placeholder="Description" value="{{ $setting->description }}">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update form <i
                                    class="icon-pencil ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Setting Ends --}}
@endsection
