<li class="nav-item">
    <a href="{{ route('student.profile') }}"
        class="nav-link {{ in_array(Route::currentRouteName(), ['student.profile']) ? 'active' : '' }}"><i
            class="icon-vcard">
        </i> <span>Profile & Info</span>
    </a>
</li>

<li class="nav-item">
    <a href="/registration"
        class="nav-link {{ in_array(Route::currentRouteName(), ['registration.index']) ? 'active' : '' }}"><i
            class="icon-clipboard2">
        </i> <span>Registration</span>
    </a>
</li>

<li
    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student-exam_results', 'student-exam_registration', 'student_ca_results']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-books"></i> <span>Assessments</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
        <li class="nav-item">
            <a href="{{ route('student-exam_results') }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['student-exam_results']) ? 'active' : '' }}">
                Exam Results
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student_ca_results') }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['student_ca_results']) ? 'active' : '' }}">
                CA Results
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('student.exam.slip.download', Auth::user()->student->id) }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['student.transcript.download']) ? 'active' : '' }}">
                Download Exam slip
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student.transcript.download', Auth::user()->student->id) }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['student.exam.slip.download']) ? 'active' : '' }}">
                Download Transcript
            </a>
        </li>
        {{--        <li class="nav-item"> --}}
        {{--            <a href="{{ route('student-exam_registration') }}" --}}
        {{--                class="nav-link {{ in_array(Route::currentRouteName(), ['student-exam_registration']) ? 'active' : '' }}"> --}}
        {{--                Exam registration --}}
        {{--            </a> --}}
        {{--        </li> --}}
    </ul>
</li>

{{-- <li
    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student.exam.slip.download', 'student.transcript.download', 'student.enrollments']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Academics </span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
        <li class="nav-item">
            <a href="{{ route('student.enrollments') }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['student.enrollments']) ? 'active' : '' }}">
                Enrollment Information
            </a>
        </li>
    </ul>
</li> --}}

<li class="nav-item">
    <a href="{{ route('student.finances') }}"
        class="nav-link {{ in_array(Route::currentRouteName(), ['student.finances']) ? 'active' : '' }}"><i
            class="icon-cash2">
        </i> <span>Finances</span>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('student_applied.rooms') }}"
        class="nav-link {{ in_array(Route::currentRouteName(), ['student_applied.rooms']) ? 'active' : '' }}"><i
            class="icon-bed2">
        </i> <span>Accommodation </span>
    </a>
</li>
