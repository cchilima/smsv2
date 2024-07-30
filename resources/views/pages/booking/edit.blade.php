@extends('layouts.master')
@section('page_title', 'Edit Booking')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Booking</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post"
                          action="{{ route('booking.update', $booking->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Hostel Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="hostel_id" id="hostel_id" onchange="getHostelRooms(this.value)"
                                        class="form-control select-search" required>
                                    <option selected
                                            value="{{ $booking->bedSpace->room->hostel->id }}"> {{ $booking->bedSpace->room->hostel->hostel_name }}</option>
                                    @foreach ($hostel as $h)
                                        <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Room <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="room_id" id="room_id" onchange="getRoomBedSpaces(this.value)"
                                        class="form-control select-search" required>
                                    <option selected
                                            value="{{ $booking->bedSpace->room->id }}"> {{ $booking->bedSpace->room->room_number }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Bed Space <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="bed_space_id" id="bed_space_id" class="form-control select-search"
                                        required>
                                    <option selected
                                            value="{{ $booking->bedSpace->id }}"> {{ $booking->bedSpace->bed_number }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Student Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="student_id" class="form-control select-search" required>
                                    <option selected
                                            value="{{ $booking->student_id }}">{{ $booking->student->user->first_name.' '.$booking->student->user->last_name }}l</option>
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
