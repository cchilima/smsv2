@php
    use App\Helpers\Qs;
@endphp
<div class="navbar navbar-expand-md navbar-dark">

    @auth
        <div class="d-md-none">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-menu7"></i>
            </button>
            {{-- <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button> --}}
        </div>
    @endauth

    <div class="collapse navbar-collapse flex-md-grow-0" id="navbar-mobile">
        @auth
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link p-0 sidebar-control sidebar-main-toggle d-none d-md-block">
                        <i class="icon-paragraph-justify3"></i>
                    </a>
                </li>

            </ul>
        @endauth

        <span class="navbar-text ml-md-3 mr-md-auto"></span>

        <ul class="navbar-nav">

            @auth
                <li class="nav-item dropdown dropdown-user">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        @php
                            $passportPhotoUrl =
                                Auth::user()->userPersonalInfo && Auth::user()->userPersonalInfo->passport_photo_path
                                    ? asset(Auth::user()->userPersonalInfo->passport_photo_path)
                                    : asset('images/default-avatar.png');

                        @endphp

                        <img style="width: 38px; height:38px;" src="{{ $passportPhotoUrl }}" class="rounded-circle"
                            alt="photo">
                        <span>{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ Qs::userIsStudent() ? '#' /*route('profile', ''Qs::hash(Auth::user()->id))*/ : route('users.show', Qs::hash(Auth::user()->id)) }}"
                            class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('settings.index') }}" class="dropdown-item"><i class="icon-cog5"></i>System
                            Settings</a>
                        <a href="{{ '#' }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endauth

        </ul>
    </div>

    <div class="py-1 ml-auto">
        <a href="{{ route('home') }}" class="d-flex align-items-center">
            <img style="max-height: 40px; height: 90%; width: auto" class="mr-2"
                src="{{ asset('images/logo-v2.png') }}" alt="ZUT Logo">
            {{-- <h4 class="text-bold text-white m-0">{{ Qs::getSystemName() }}</h4> --}}
        </a>
    </div>
</div>
