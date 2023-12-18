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
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('marital-statuses.update', $maritalStatus->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Status <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="status" required type="text" class="form-control" placeholder="Marital Status" value="{{ $maritalStatus->status }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Description <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="description" required type="text" class="form-control" placeholder="Description" value="{{ $maritalStatus->description }}">
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
