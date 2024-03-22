@extends('layouts.master')
@section('page_title', 'Manage Users')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Users</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-users" class="nav-link active" data-toggle="tab">Manage Users</a></li>
                <li class="nav-item"><a href="#new-user" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New User</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-users">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>User Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->first_name }}  {{ $user->last_name }}</td>
                                <td>{{ $user->userType->name }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $user->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" class="hidden">@csrf @method('delete')</form>
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

                <div class="tab-pane fade" id="new-user">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header header-elements-inline">
                                    <h6 class="card-title">Create User</h6>
                                    {!! Qs::getPanelOptions() !!}
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('users.store') }}">
                                                @csrf @method('POST')

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">First name <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <input name="first_name" required type="text" class="form-control" placeholder="First name">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">Middle name </label>
                                                    <div class="col-lg-9">
                                                        <input name="middle_name" type="text" class="form-control" placeholder="Middle name">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">Last name <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <input name="last_name" required type="text" class="form-control" placeholder="Last name">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">Email Address: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <input value="{{ old('email') }}" required class="form-control" placeholder="Email Address" name="email" type="text">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">Password: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <input value="{{ old('password') }}" required class="form-control" placeholder="Password" name="password" type="password">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">Confirm Password: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <input value="{{ old('password_confirmation') }}" required class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">Gender: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <select class="select form-control" required id="gender" name="gender" data-fouc data-placeholder="Choose..">
                                                            <option value=""></option>
                                                            <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                                            <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label font-weight-semibold">User Type: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <select class="select form-control select-search" required id="user_type" name="user_type_id" data-fouc data-placeholder="Choose..">
                                                            <option value=""></option>
                                                            @foreach($userTypes as $type)
                                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                                            @endforeach
                                                        </select>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User List Ends --}}
@endsection
