<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="ZUT">

    <title> @yield('page_title') | {{ config('app.name') }} </title>

    @include('partials.inc_top')
</head>

<body
    class="{{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.show', 'ttr.manage', 'ttr.show']) ? 'sidebar-xs' : '' }}">

    @include('partials.top_menu')
    <div class="page-content">
        @auth
            @include('partials.menu')
        @endauth
        <div class="content-wrapper">
            @auth
            @endauth
            @include('partials.header')

            <div class="content">
                {{-- Error Alert Area --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                        @foreach ($errors->all() as $er)
                            <span><i class="icon-arrow-right5"></i> {{ $er }}</span> <br>
                        @endforeach

                    </div>
                @endif
                <div id="ajax-alert" style="display: none"></div>

                {{ $slot }}

            </div>

        </div>
    </div>

    @include('partials.inc_bottom')
    @stack('scripts')
</body>

</html>
