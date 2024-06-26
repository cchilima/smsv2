@extends('layouts.master')
@section('page_title', 'Manage Bed Spaces')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Bed Spaces</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-statuses" class="nav-link active" data-toggle="tab">Manage Bed Spaces</a></li>
                <li class="nav-item"><a href="#new-status" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Bed Space</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-statuses">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Room Number</th>
                            <th>Bed Space Number</th>
                            <th>Is Available</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bed_space as $b)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $b->room->room_number }}</td>
                                <td>{{ $b->bed_number }}</td>
                                <td>{{ $b->is_available }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('bed-space.edit', $b->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $b->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $b->id }}" action="{{ route('bed-space.destroy', $b->id) }}" class="hidden">@csrf @method('delete')</form>
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

                <div class="tab-pane fade" id="new-status">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('bed-space.store')  }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Room Name/Number <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="room_id" class="form-control select-search" required>
                                            <option value=""> Choose Room</option>
                                            @foreach ($rooms as $r)
                                                <option value="{{ $r->id }}">{{ $r->room_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Bed Number <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="bed_number" value="{{ old('bed_number') }}" required type="text" class="form-control" placeholder="Bed Number">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Is Available For Booking<span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="is_available" class="form-control select-search" required>
                                            <option selected value="true"> True</option>
                                            <option value="false"> False</option>
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
