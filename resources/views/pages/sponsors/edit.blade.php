@extends('layouts.master')
@section('page_title', 'Edit Marital Status')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Marital Status</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('sponsors.update', $sponsor->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $sponsor->name }}" required type="text"
                                       class="form-control"
                                       placeholder="GRZ | CDF | NGO">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="description" value="{{ $sponsor->description }}" required type="text"
                                       class="form-control" placeholder="Description">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Phone <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="phone" value="{{ $sponsor->phone }}" type="email"
                                       class="form-control" placeholder="phone">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Email <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="email" value="{{ $sponsor->email }}"  type="text"
                                       class="form-control" placeholder="email">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update form <i class="icon-pencil ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Marital Status Ends --}}
@endsection
