@extends('layouts.master')
@section('page_title', 'Hostel Room Booking Management')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Bookings</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-bookings" class="nav-link active" data-toggle="tab">Bookings</a></li>
                <li class="nav-item"><a href="#new-booking" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New Booking</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-bookings">
                    <livewire:datatables.accommodation.bookings />

                    <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>student Number</th>
                                <th>student Name</th>
                                <th>Hostel</th>
                                <th>Room number</th>
                                <th>Bed number</th>
                                <th>Expiring Date</th>
                                <th>Booking Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($open as $booking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $booking->student_id }}</td>
                                    <td>{{ $booking->student->user->first_name . ' ' . $booking->student->user->last_name }}
                                    </td>
                                    <td>{{ $booking->bedSpace->room->hostel->hostel_name }}</td>
                                    <td>{{ $booking->bedSpace->room->room_number }}</td>
                                    <td>{{ $booking->bedSpace->bed_number }}</td>
                                    <td>{{ date('j F Y', strtotime($booking->expiration_date)) }}</td>
                                    <td>{{ date('j F Y', strtotime($booking->booking_date)) }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if (Qs::userIsTeamSA())
                                                        <a href="{{ route('booking.edit', $booking->id) }}"
                                                            class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    @if (Qs::userIsSuperAdmin())
                                                        <a id="{{ $booking->id }}" onclick="confirmDelete(this.id)"
                                                            href="#" class="dropdown-item"><i class="icon-trash"></i>
                                                            Delete</a>
                                                        <form method="post" id="item-delete-{{ $booking->id }}"
                                                            action="{{ route('booking.destroy', $booking->id) }}"
                                                            class="hidden">
                                                            @csrf @method('delete')</form>

                                                        <form class="ajax-store" method="post"
                                                            action="{{ route('confirmation.booking') }}">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $booking->id }}">
                                                            <input type="hidden" name="student_id"
                                                                value="{{ $booking->student_id }}">
                                                            <div class="text-right">
                                                                <button id="ajax-btn" type="submit"
                                                                    class="dropdown-item"><i class="icon-paperplane ml-2">
                                                                        Confirm Booking</i></button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="new-booking">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('booking.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Hostel Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="hostel_id" id="hostel_id" onchange="getHostelRooms(this.value)"
                                            class="form-control select-search" required>
                                            <option value=""> Choose Hostel</option>
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
                                            <option value=""> Choose Hostel</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Bed Space <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="bed_space_id" id="bed_space_id" class="form-control select-search"
                                            required>
                                            <option value=""> Choose Bed Space</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Student Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="student_id" id="student_id" class="form-control select-search"
                                            required>
                                            <option value=""> Choose Student</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Marital Status List Ends --}}
@endsection
