@extends('layouts.master')
@section('page_title', 'Edit - '.$departments->name.' Department')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Department</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('departments.update', $departments->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $departments->name }}" required type="text" class="form-control" placeholder="Name of Department">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Description <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="description" value="{{ $departments->description }}" required type="text" class="form-control" placeholder="description">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Upload Department Photo:</label>
                            <div class="col-lg-9">
                                <div class="mb-3">
                                    <img style="width: 100px" height="100px" src="{{ $departments->cover }}" alt="">
                                </div>
                                <input name="cover" accept="image/*" type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                                <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb  </span>
                            </div>
                        </div>

                        <div class="text-right">
                            <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class Edit Ends--}}

@endsection
