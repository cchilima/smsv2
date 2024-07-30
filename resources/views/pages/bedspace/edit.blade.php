@extends('layouts.master')
@section('page_title', 'Edit Room')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Room</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('bed-space.update', $bed_space->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Room Name/Number <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="room_id" class="form-control select-search" required>
                                    <option value="{{ $bed_space->room_id }}">{{ $bed_space->room->room_number }}</option>
                                    @foreach ($rooms as $r)
                                        <option value="{{ $r->id }}">{{ $r->room_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Bed Number <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="bed_number" value="{{ $bed_space->bed_number }}" required type="text" class="form-control" placeholder="Bed Number">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Is Available For Booking<span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="is_available" class="form-control select-search" required>
                                    <option selected value="{{ $bed_space->is_available }}"> {{ $bed_space->is_available  }}</option>
                                    <option value="false"> False</option>
                                    <option value="True"> True</option>
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
