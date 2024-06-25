@extends('layouts.master')
@section('page_title', 'Create Marital Status')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Create Marital Status</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('marital-statuses.store') }}">
                        @csrf @method('POST')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Status<span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="status" required type="text" class="form-control" placeholder="marital status">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Description<span></span></label>
                            <div class="col-lg-9">
                                <input name="description" type="text" class="form-control" placeholder="description">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Marital Status Ends --}}
@endsection
