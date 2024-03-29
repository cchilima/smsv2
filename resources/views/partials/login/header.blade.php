<!-- Main navbar -->
@php
    use App\Helpers\Qs;
@endphp
<div class="navbar navbar-expand navbar-dark">
    {{-- <div class="mt-2 mr-5">
        <a href="#" class="d-inline-block">
            <h4 class="text-bold text-white">{{ Qs::getSystemName() }}</h4>
        </a>
    </div> --}}

    {{-- <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
    </div> --}}

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a href="{{ route('application.initiate') }}" class="navbar-nav-link py-1 ">
                    <i class="icon-pencil7"></i>
                    <span class="ml-2">Apply Now</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
