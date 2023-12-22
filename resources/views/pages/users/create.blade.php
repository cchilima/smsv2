@extends('layouts.master')
@section('page_title', 'Create User')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Create User</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
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
                            <label class="col-lg-3 col-form-label font-weight-semibold">Gender: <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select class="select form-control" required id="gender" name="gender" data-fouc data-placeholder="Choose..">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
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
                            <label class="col-lg-3 col-form-label font-weight-semibold">User Type: <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select class="select form-control" required id="user_type" name="user_type_id" data-fouc data-placeholder="Choose..">
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
    
@endsection
