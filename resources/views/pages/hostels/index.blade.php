@extends('layouts.master')
@section('page_title', 'Manage Hostels')
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
                <li class="nav-item"><a href="#all-hostel" class="nav-link active" data-toggle="tab">Manage Hostels</a></li>
                <li class="nav-item"><a href="#new-hostel" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Hostel</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-hostel">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hostels as $h)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $h->hostel_name }}</td>
                                <td>{{ $h->location }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('hostels.edit', $h->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $h->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $h->id }}" action="{{ route('hostels.destroy', $h->id) }}" class="hidden">@csrf @method('delete')</form>
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

                <div class="tab-pane fade" id="new-hostel">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('hostels.store')  }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="hostel_name" value="{{ old('hostel_name') }}" required type="text" class="form-control" placeholder="name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Location <span class="text-danger"></span></label>
                                    <div class="col-lg-9">
                                        <input name="location" value="{{ old('location') }}" type="text" class="form-control" placeholder="location">
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
