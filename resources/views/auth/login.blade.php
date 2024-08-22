@php
    use App\Helpers\Qs;
@endphp

@extends('layouts.login_master')

@section('content')
    <div class="page-content login-cover">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">

                <!-- Login card -->
                <form class="login-form " method="post" action="{{ route('login') }}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="{{ asset('images/logo-v2.png') }}" alt="ZUT logo" class="w-25 mb-1">
                                <h5 class="mb-0">{{ Qs::getSystemName() }}</h5>
                                <span class="d-block text-muted">Log into your account</span>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-styled-left alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <span class="font-weight-semibold">Oops!</span> {{ implode('<br>', $errors->all()) }}
                                </div>
                            @endif

                            <div class="form-group ">
                                <input type="number" class="form-control" name="email" value="{{ old('identity') }}"
                                    placeholder="Student ID">
                            </div>

                            <div class="form-group ">
                                <input required name="password" type="password" class="form-control"
                                    placeholder="{{ __('Password') }}">

                            </div>

                            <div class="form-group d-flex align-items-center">
{{--                                <div class="form-check mb-0">--}}
{{--                                    <label class="form-check-label">--}}
{{--                                        <input type="checkbox" name="remember" class="form-input-styled"--}}
{{--                                            {{ old('remember') ? 'checked' : '' }} data-fouc>--}}
{{--                                        Remember--}}
{{--                                    </label>--}}
{{--                                </div>--}}

                                <a href="{{ route('password.request') }}" class="ml-auto">Forgot password?</a>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Sign in <i
                                        class="icon-circle-right2 ml-2"></i></button>
                            </div>

                            <div class="form-group">
                                <a href="{{ route('staff') }}" class="btn btn-light btn-block"> Sign as Staff <i
                                        class="icon-circle-right2 ml-2"></i></a>
                            </div>

                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>
@endsection
