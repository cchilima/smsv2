@extends('layouts.master')
@section('page_title', 'Manage Marital Statuses')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Marital Statuses</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-rooms" class="nav-link active" data-toggle="tab">Manage Rooms</a></li>
                <li class="nav-item"><a href="#new-rooms" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Rooms</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-rooms">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Room Number</th>
                            <th>Hostel</th>
                            <th>Capacity</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rooms as $room)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $room->room_number }}</td>
                                <td>{{ $room->hostel->hostel_name }}</td>
                                <td>{{ $room->capacity }}</td>
                                <td>{{ $room->gender }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('rooms.edit', $room->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $room->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $room->id }}" action="{{ route('rooms.destroy', $room->id) }}" class="hidden">@csrf @method('delete')</form>
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

                <div class="tab-pane fade" id="new-rooms">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('rooms.store')  }}">
                                @csrf
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
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Room Number <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="room_number" value="{{ old('room_number') }}" required type="text" class="form-control" placeholder="room_number">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Capacity <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="capacity" value="{{ old('description') }}" required type="text" class="form-control" placeholder="Capacity">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Gender <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="gender" class="form-control select-search" required>
                                            <option value=""> Choose Hostel</option>
                                                <option value="Male">Male</option>
                                            <option value="Female">Female</option>
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
            </div>
        </div>
    </div>

    {{-- Marital Status List Ends --}}
@endsection
