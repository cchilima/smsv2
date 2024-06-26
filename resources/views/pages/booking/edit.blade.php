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
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('booking.update', $maritalStatus->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Hostel Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="hostel_id" class="form-control select-search" required>
                                    <option value=""> Choose Hostel</option>
                                    @foreach ($hostels as $h)
                                        <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Hostel Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="hostel_id" class="form-control select-search" required>
                                    <option value=""> Choose Hostel</option>
                                    @foreach ($hostels as $h)
                                        <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>
                                    @endforeach
                                </select>
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
