@extends('layouts.master')
@section('page_title', 'Accommodation - ' . auth()->user()->first_name . ' ' . auth()->user()->last_name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Accommodation Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">

                        <li class="nav-item">
                            <a href="#status" class="nav-link active" data-toggle="tab">{{ 'My Applications' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#new" class="nav-link" data-toggle="tab">{{ 'Status' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="status">
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
                                @foreach($closed as $c)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $c->student_id }}</td>
                                        <td>{{ $c->student->user->first_name.' '.$c->student->user->last_name }}</td>
                                        <td>{{ $c->bedSpace->room->hostel->hostel_name }}</td>
                                        <td>{{ $c->bedSpace->room->room_number }}</td>
                                        <td>{{ $c->bedSpace->bed_number }}</td>
                                        <td>{{ date('j F Y', strtotime($c->expiration_date)) }}</td>
                                        <td>{{ date('j F Y', strtotime($c->booking_date)) }}</td>
                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        @if(Qs::userIsTeamSA())
                                                            <a href="{{ route('booking.edit', $c->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                        @endif
                                                        @if(Qs::userIsSuperAdmin())
                                                            <a id="{{ $c->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                            <form method="post" id="item-delete-{{ $c->id }}" action="{{ route('booking.destroy', $c->id) }}" class="hidden">@csrf @method('delete')</form>
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

                        <div class="tab-pane fade show" id="new">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="ajax-store" method="post" action="{{ route('student.apply_accommodation')  }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Hostel Name <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="hostel_id" id="hostel_ids" onchange="getHostelRoomsOne(this.value)" class="form-control select-search" required>
                                                    <option value=""> Choose Hostel</option>
                                                    @foreach ($hostel as $h)
                                                        <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Room <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="room_ids" id="room_ids" onchange="getRoomBedSpacesStudent(this.value)" class="form-control select-search" required>
                                                    <option value=""> Choose Hostel</option>
                                                    {{--                                            @foreach ($hostels as $h)--}}
                                                    {{--                                                <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>--}}
                                                    {{--                                            @endforeach--}}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Bed Space <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="bed_space_id" id="bed_space_ids" class="form-control select-search" required>
                                                    <option value=""> Choose Bed Space</option>
                                                    {{--                                            @foreach ($hostels as $h)--}}
                                                    {{--                                                <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>--}}
                                                    {{--                                            @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Student Name <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="student_id" id="student_ids" class="form-control select-search" required>
                                                    <option value=""> Choose Hostel</option>
                                                    {{--                                            @foreach ($hostels as $h)--}}
                                                    {{--                                                <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>--}}
                                                    {{--                                            @endforeach--}}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade show" id="payment-history">

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
