@php
    use App\Helpers\Qs;
@endphp
<div class="navbar navbar-expand-lg navbar-light justify-content-center ">
    <div class="text-center d-lg-none w-100">
        {{-- <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
            <i class="icon-unfold mr-2"></i>
            More Links
        </button> --}}
    </div>

    {{-- <div class="navbar-collapse collapse" id="navbar-footer"> --}}
    <div class="" id="navbar-footer">
        <span class="navbar-text">
            {{ Qs::getSystemName() }} &copy; {{ date('Y') }} by <a target="_blank" href="https://zut.ac.zm">SMART
                Center</a>
        </span>

        <ul class="navbar-nav ml-lg-auto">
            {{-- <li class="nav-item"><a href="{{ 00 }}" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i> Privacy Policy</a></li> --}}
        </ul>
    </div>
</div>
