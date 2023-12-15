{{--<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">--}}
{{--    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Finances</span></a>--}}
{{--    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">--}}
{{--        <li class="nav-item"><a href="{{ route('calendar') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['calendar']) ? 'active' : '' }}">Invoices</a></li>--}}
{{--        <li class="nav-item"><a href="{{ route('intakes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['intakes.index','intakes.edit']) ? 'active' : '' }}">Statements</a></li>--}}
{{--    </ul>--}}
{{--</li>--}}
<li class="nav-item">
    <a href="{{ route('student_finance') }}" class="nav-link {{ (Route::is('student_finance')) ? 'active' : '' }}">
        <i class="icon-home4"></i>
        <span>Finances</span>
    </a>
</li>
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student-exam_results', 'student-exam_registration', 'student_ca_results']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Examinations</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
        <li class="nav-item"><a href="{{ route('student-exam_results') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student-exam_results']) ? 'active' : '' }}">Results</a></li>
        <li class="nav-item"><a href="{{ route('student_ca_results') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student_ca_results']) ? 'active' : '' }}">CA Results</a></li>
        <li class="nav-item"><a href="{{ route('student-exam_registration') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student-exam_registration']) ? 'active' : '' }}">Exam registration</a></li>
    </ul>
</li>
{{--<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">--}}
{{--    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Time Tables</span></a>--}}
{{--    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">--}}
{{--        <li class="nav-item"><a href="{{ route('calendar') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['calendar']) ? 'active' : '' }}">Exam Timetable</a></li>--}}
{{--        <li class="nav-item"><a href="{{ route('intakes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['intakes.index','intakes.edit']) ? 'active' : '' }}">Student Time table</a></li>--}}
{{--    </ul>--}}
{{--</li>--}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> E Learning</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
        <li class="nav-item"><a href="{{ route('calendar') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['calendar']) ? 'active' : '' }}">Modle</a></li>
    </ul>
</li>
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student.index','MyPrograms']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Programs</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
        <li class="nav-item"><a href="{{ route('student.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student.index']) ? 'active' : '' }}">Registration</a></li>
        <li class="nav-item"><a href="{{ route('my-program') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my-program']) ? 'active' : '' }}">My Programs</a></li>
     </ul>
</li>
{{--<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">--}}
{{--    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Accommodation</span></a>--}}
{{--    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">--}}
{{--        <li class="nav-item"><a href="{{ route('calendar') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['calendar']) ? 'active' : '' }}">Application</a></li>--}}
{{--    </ul>--}}
{{--</li>--}}

<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['changePrograms', 'exemptions', 'Withdrawal_Deferment', 'Add_Drop_courses','change_mode']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Application</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
        <li class="nav-item"><a href="{{ route('changePrograms') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['changePrograms']) ? 'active' : '' }}">Change of Program</a></li>
        <li class="nav-item"><a href="{{ route('exemptions') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['exemptions']) ? 'active' : '' }}">Exemptions</a></li>
        <li class="nav-item"><a href="{{ route('Withdrawal_Deferment') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['Withdrawal_Deferment']) ? 'active' : '' }}">Withdrawal/Deferment</a></li>
        <li class="nav-item"><a href="{{ route('Add_Drop_courses') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['Add_Drop_courses']) ? 'active' : '' }}">Add/Drop Course(s)</a></li>
        <li class="nav-item"><a href="{{ route('change_mode') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['change_mode']) ? 'active' : '' }}">Change of Study Mode</a></li>
    </ul>
</li>
