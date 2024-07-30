{{--Manage Settings--}}
<li class="nav-item">
    <a href="{{ route('settings.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['settings.index',]) ? 'active' : '' }}"><i class="icon-gear"></i> <span>Settings</span></a>
</li>


