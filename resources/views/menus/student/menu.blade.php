
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student-exam_results', 'student-exam_registration', 'student_ca_results']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Examinations</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
        <li class="nav-item"><a href="{{ route('student-exam_results') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student-exam_results']) ? 'active' : '' }}">Results</a></li>
        <li class="nav-item"><a href="{{ route('student_ca_results') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student_ca_results']) ? 'active' : '' }}">CA Results</a></li>
        <li class="nav-item"><a href="{{ route('student-exam_registration') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student-exam_registration']) ? 'active' : '' }}">Exam registration</a></li>
    </ul>
</li>

<li class="nav-item">
    <a href="/registration"
        class="nav-link {{ in_array(Route::currentRouteName(), ['registration.index']) ? 'active' : '' }}"><i
        class="icon-user"></i> <span>Registration</span>
    </a>
</li>
