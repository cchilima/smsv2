@php
    use App\Helpers\Qs;
@endphp
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        {{-- <div class="sidebar-user">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="mr-3">

                        @php
                            $passportPhotoUrl = !Auth::user()->userPersonalInfo->passport_photo_path
                                ? asset('images/default-avatar.png')
                                : asset(Auth::user()->userPersonalInfo->passport_photo_path);
                        @endphp

                        <a href="{{ '#' }}"><img src="{{ $passportPhotoUrl }}" width="38" height="38"
                                class="rounded-circle" alt="photo"></a>
                    </div>

                    <div class="media-body d-flex">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->first_name }}</div>
                        &nbsp;(Type: {{ ucwords(str_replace('_', ' ', Auth::user()->user_type_id)) }})
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ '#' }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- /user menu -->
        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Academics --}}
                @if (Qs::userIsSuperAdmin() || Qs::userIsAdmin() || Qs::userIsDIF() || Qs::userIsED())

                    {{-- Administrative --}}

                    @if (true)
                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                                'intakes.index',
                                'intakes.edit',
                                'prerequisites.index',
                                'prerequisites.edit',
                                'schools.index',
                                'schools.edit',
                                'classes.index',
                                'classes.edit',
                                'study-modes.index',
                                'period-types.index',
                                'period-types.edit',
                                'departments.index',
                                'departments.edit',
                                'departments.show',
                                'programs.index',
                                'programs.edit',
                                'programs.show',
                                'courses.index',
                                'courses.edit',
                                'qualifications.index',
                                'qualifications.edit',
                                'levels.index',
                                'levels.edit',
                            ])
                                ? 'nav-item-expanded nav-item-open'
                                : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-library2"></i> <span> Departments &
                                    Programs</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
                                {{-- Manage Departments --}}

                                <li class="nav-item">
                                    <a href="{{ route('schools.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['schools.index', 'schools.edit']) ? 'active' : '' }}">
                                        <span>Schools</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('departments.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['departments.index', 'departments.edit', 'departments.show']) ? 'active' : '' }}">
                                        <span>Departments</span></a>
                                </li>
                                {{-- Manage programs --}}
                                <li class="nav-item">
                                    <a href="{{ route('programs.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['programs.index', 'programs.edit', 'programs.show']) ? 'active' : '' }}">
                                        <span>Programs</span></a>
                                </li>

                                {{-- Manage courses --}}
                                <li class="nav-item">
                                    <a href="{{ route('courses.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['courses.index', 'courses.edit']) ? 'active' : '' }}">
                                        <span>Courses</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('qualifications.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['qualifications.index', 'qualifications.edit']) ? 'active' : '' }}">
                                        <span>Qualifications</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('levels.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['levels.index', 'levels.edit']) ? 'active' : '' }}">
                                        <span>Course Levels</span></a>
                                </li>
                                {{-- Manage Study modes --}}
                                <li class="nav-item">
                                    <a href="{{ route('study-modes.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['study-modes.index', 'study-modes.edit']) ? 'active' : '' }}">
                                        <span>Study Modes</span></a>
                                </li>
                                {{-- Academic MANAGEMENT --}}
                                <li class="nav-item">
                                    <a href="{{ route('period-types.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['period-types.index', 'period-types.edit']) ? 'active' : '' }}">
                                        <span>Academic Period Types</span></a>
                                </li>
                                {{-- Manage Prere --}}
                                <li class="nav-item">
                                    <a href="{{ route('prerequisites.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['prerequisites.index', 'prerequisites.edit']) ? 'active' : '' }}">
                                        <span>Prerequisites</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('intakes.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['intakes.index', 'intakes.edit']) ? 'active' : '' }}">
                                        <span>Intake</span></a>
                                </li>
                            </ul>
                        </li>

                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                                'academic-period-management.index',
                                'academic-period-fees.edit',
                                'academic-period-management.edit',
                                'academic-periods.create',
                                'academic-periods.edit',
                                'academic-periods.index',
                                'academic-period-classes.index',
                                'academic-period-classes.edit',
                                'academic-period-classes.create',
                            ])
                                ? 'nav-item-expanded nav-item-open'
                                : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span>
                                    Academics</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Manage Academic Period">
                                <li class="nav-item">
                                    <a href="{{ route('academic-periods.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['academic-period-management.index', 'academic-period-management.edit', 'academic-period-fees.edit', 'academic-periods.create', 'academic-periods.edit', 'academic-periods.index']) ? 'active' : '' }}">
                                        <span>Academic Periods</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('academic-period-classes.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['academic-period-classes.create', 'academic-period-classes.edit', 'academic-period-classes.index']) ? 'active' : '' }}">
                                        <span>Academic Period Classes</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('enrollments.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['enrollments.index']) ? 'active' : '' }}">
                                        <span>Enrollments</span></a>
                                </li>
                            </ul>
                        </li>

                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                                'credit.notes',
                                'student.list',
                                'student.list.post',
                                'creditors',
                                'aged.receivables',
                                'transactions',
                                'transaction-results',
                                'revenue.analysis',
                                'revenue-revenue-result',
                                'invoices',
                                'invoices-results',
                                'failed.transaction',
                                'fees.create',
                                'fees.edit',
                                'fees.index',
                                'payment-methods.create',
                                'payment-methods.edit',
                                'payment-methods.index',
                            ])
                                ? 'nav-item-expanded nav-item-open'
                                : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-cash3"></i> <span>
                                    Accounting</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Fees">

                                <li class="nav-item">
                                    <a href="{{ route('fees.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['fees.create', 'fees.edit', 'fees.index']) ? 'active' : '' }}">
                                        <span>Fees</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('payment-methods.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['payment-methods.create', 'payment-methods.edit', 'payment-methods.index']) ? 'active' : '' }}">
                                        <span>Payment Methods</span></a>
                                </li>

                                @if (Qs::userIsDIF() || Qs::userIsED())
                                    <li class="nav-item">
                                        <a href="{{ route('accounting.approve_credit_notes') }}"
                                            class="nav-link {{ in_array(Route::currentRouteName(), ['accounting.approve_credit_notes']) ? 'active' : '' }}">
                                            <span>Approve Credit Notes</span></a>
                                    </li>
                                @endif

                                @if (true)
                                    <li
                                        class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                                            'credit.notes',
                                            'student.list',
                                            'student.list.post',
                                            'creditors',
                                            'aged.receivables',
                                            'revenue.analysis',
                                            'revenue-revenue-result',
                                            'invoices',
                                            'invoices-results',
                                            'transactions',
                                            'transaction-results',
                                            'failed.transaction',
                                        ])
                                            ? 'nav-item-expanded nav-item-open'
                                            : '' }} ">
                                        <a href="#" class="nav-link">Reports</a>
                                        <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                                            @if (true)
                                                <li
                                                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['revenue.analysis', 'revenue-revenue-result', 'invoices', 'invoices-results']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                    <a href="#"
                                                        class="nav-link {{ in_array(Route::currentRouteName(), ['revenue.analysis', 'revenue-revenue-result']) ? 'active' : '' }}">Revenue</a>
                                                    <ul class="nav nav-group-sub">
                                                        <li class="nav-item"><a href="{{ route('revenue.analysis') }}"
                                                                class="nav-link {{ in_array(Route::currentRouteName(), ['revenue.analysis', 'revenue-revenue-result']) ? 'active' : '' }}">Revenue
                                                                Analysis</a>
                                                        </li>
                                                        <li class="nav-item"><a href="{{ route('invoices') }}"
                                                                class="nav-link {{ in_array(Route::currentRouteName(), ['invoices', 'invoices-results']) ? 'active' : '' }}">Invoices</a>
                                                        </li>
                                                    </ul>

                                                </li>
                                            @endif
                                            @if (true)
                                                <li
                                                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['transactions', 'transaction-results', 'failed.transaction', 'credit.notes', 'aged.receivables']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                    <a href="#"
                                                        class="nav-link {{ in_array(Route::currentRouteName(), ['transactions', 'aged.receivables', 'failed.transaction']) ? 'active' : '' }}">
                                                        Receivables</a>
                                                    <ul class="nav nav-group-sub">
                                                        <li class="nav-item"><a href="{{ route('transactions') }}"
                                                                class="nav-link {{ in_array(Route::currentRouteName(), ['transactions']) ? 'active' : '' }}">Transactions</a>
                                                        </li>
                                                        <li class="nav-item"><a
                                                                href="{{ route('failed.transaction') }}"
                                                                class="nav-link {{ in_array(Route::currentRouteName(), ['failed.transaction']) ? 'active' : '' }}">Failed
                                                                Online Transactions</a>
                                                        </li>
                                                        <li class="nav-item"><a
                                                                href="{{ route('aged.receivables') }}"
                                                                class="nav-link {{ in_array(Route::currentRouteName(), ['aged.receivables']) ? 'active' : '' }}">Aged
                                                                Receivables</a>
                                                        </li>
                                                        <li class="nav-item"><a href="#" class="nav-link">Bank
                                                                Reconciliation</a>
                                                        </li>
                                                    </ul>

                                                </li>
                                            @endif
                                            @if (true)
                                                <li
                                                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['credit.notes', 'student.list', 'student.list.post']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                    <a href="#"
                                                        class="nav-link {{ in_array(Route::currentRouteName(), ['student.list', 'credit.notes']) ? 'active' : '' }}">General</a>
                                                    <ul class="nav nav-group-sub">
                                                        <li class="nav-item"><a href="{{ route('student.list') }}"
                                                                class="nav-link  {{ in_array(Route::currentRouteName(), ['student.list', 'student.list.post']) ? 'active' : '' }}">Student
                                                                List</a>
                                                        </li>
                                                        <li class="nav-item"><a href="#"
                                                                class="nav-link ">Chart of Accounts</a>
                                                        </li>
                                                        <li class="nav-item"><a href="{{ route('credit.notes') }}"
                                                                class="nav-link {{ in_array(Route::currentRouteName(), ['credit.notes']) ? 'active' : '' }}">Credit
                                                                Notes</a>
                                                        </li>
                                                    </ul>

                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                {{-- <li class="nav-item">
                                    <a href="{{ route('application.pending_collection') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['application.pending_collection']) ? 'active' : '' }}">
                                        <span>Collect Application Payment</span></a>
                                </li> --}}

                            </ul>
                        </li>

                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['search', 'students.create', 'students.edit', 'students.list']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-users"></i> <span>
                                    Students</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Students">

                                {{-- <li class="nav-item">
                                    <a href="{{ route('students.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['students.create']) ? 'active' : '' }}">
                                        <span>Admit student</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('search') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['search']) ? 'active' : '' }}">
                                        <span>Student Information</span></a>
                                </li> --}}

                                <li class="nav-item">
                                    <a href="{{ route('students.list') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['students.list']) ? 'active' : '' }}">
                                        <span>Student List</span></a>
                                </li>

                            </ul>
                        </li>

                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['application.index', 'application.show', 'start-application', 'application.complete_application', 'application.save_application', 'application.summary_reports']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-user-plus"></i> <span>
                                    Admissions</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Admissions">
                                <li class="nav-item">
                                    <a href="{{ route('start-application') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['applications.initiate']) ? 'active' : '' }}">
                                        <span>New Student Application</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('application.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['applications.index']) ? 'active' : '' }}">
                                        <span>Applications</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('application.summary_reports') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['application.summary_reports']) ? 'active' : '' }}">
                                        <span>Reports</span></a>
                                </li>
                            </ul>
                        </li>

                        </li>

                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['hostels.create', 'hostels.edit', 'hostels.index', 'rooms.index', 'rooms.edit', 'bookings.index', 'bookings.edit', 'bed-spaces.index', 'bed-spaces.edit']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-office"></i> <span>
                                    Accommodation</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Profile">
                                <li class="nav-item">
                                    <a href="{{ route('hostels.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['hostels.create', 'hostels.edit', 'hostels.index']) ? 'active' : '' }}">
                                        <span>Hostels</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('rooms.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['rooms.create', 'rooms.edit', 'rooms.index']) ? 'active' : '' }}">
                                        <span>Rooms</span></a>
                                </li>

                                <li class="nav-item">

                                    <a href="{{ route('bed-spaces.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['bed-spaces.create', 'bed-spaces.edit', 'bed-spaces.index']) ? 'active' : '' }}">
                                        <span>Bed Spaces</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('bookings.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['bookings.create', 'bookings.edit', 'bookings.index']) ? 'active' : '' }}">
                                        <span>Bed Space Booking</span></a>
                                </li>

                            </ul>
                        </li>

                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['audit.trail.reports', 'student.list.reports', 'registers.reports', 'enrollments.reports']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-file-stats"></i>Reports</a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Users">
                                <li class="nav-item">
                                    <a href="{{ route('enrollments.reports') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['enrollments.reports']) ? 'active' : '' }}">Enrollments</a>

                                <li class="nav-item">
                                    <a href="{{ route('registers.reports') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['registers.reports']) ? 'active' : '' }}">Exam
                                        Registers</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('student.list.reports') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['student.list.reports']) ? 'active' : '' }}">Student
                                        List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('audit.trail.reports') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['audit.trail.reports']) ? 'active' : '' }}">
                                        <span>Audit Trail</span></a>
                                </li>

                            </ul>
                        </li>

                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                                'students.upload-photos',
                                'announcements.index',
                                'marital-statuses.create',
                                'marital-statuses.edit',
                                'marital-statuses.index',
                                'countries.index',
                                'countries.edit',
                                'provinces.index',
                                'provinces.edit',
                                'towns.index',
                                'towns.edit',
                                'audits.index',
                            ])
                                ? 'nav-item-expanded nav-item-open'
                                : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-equalizer"></i> <span>
                                    Other</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Profile">
                                <li class="nav-item">
                                    <a href="{{ route('students.upload-photos') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['students.upload-photos']) ? 'active' : '' }}">
                                        <span>Upload Student Photos</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('announcements.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['announcements.create', 'announcements.edit', 'announcements.index']) ? 'active' : '' }}">
                                        <span>Announcements</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('countries.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['countries.create', 'countries.edit', 'countries.index']) ? 'active' : '' }}">
                                        <span>Countries</span></a>
                                </li>
                                <li class="nav-item">

                                    <a href="{{ route('provinces.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['provinces.create', 'provinces.edit', 'provinces.index']) ? 'active' : '' }}">
                                        <span>Provinces</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('towns.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['towns.create', 'towns.edit', 'towns.index']) ? 'active' : '' }}">
                                        <span>Towns</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('marital-statuses.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['maritalStatues.create', 'maritalStatues.edit', 'maritalStatues.index']) ? 'active' : '' }}">
                                        <span>Marital Statuses</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('audits.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['audits.create', 'audits.edit', 'audits.index']) ? 'active' : '' }}">
                                        <span>Audit Reports</span></a>
                                </li>

                            </ul>
                        </li>

                    @endif

                @endif
                @if (Qs::userIsSuperAdmin() || Qs::userIsAdmin())
                    <li
                        class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                            'getPublishProgramsCas',
                            'exams.index',
                            'exams.edit',
                            'assessments.index',
                            'assessments.edit',
                            'assessments.store',
                            'classAssessments.index',
                            'classAssessments.edit',
                            'classAssessments.show',
                            'classAssessments.store',
                            'classAssessments.create',
                            'assessments.class-lists.index',
                            'import.process',
                            'getPublishPrograms',
                            'getPramResults',
                            'myClassStudentList',
                            'myClassList',
                            'reports.index',
                            'getPramResultsLevel',
                            'program-list',
                        ])
                            ? 'nav-item-expanded nav-item-open'
                            : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-books"></i> <span> Assessments</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Manage Exams">
                            @if (true)
                                {{-- Assessment Types --}}
                                @if (!Qs::userIsInstructor())
                                    <li class="nav-item">
                                        <a href="{{ route('assessments.index') }}"
                                            class="nav-link {{ Route::is('assessments.index') ? 'active' : '' }}">Assessment
                                            Types</a>
                                    </li>
                                    {{-- Tabulation Sheet --}}
                                    <li class="nav-item">
                                        <a href="{{ route('classAssessments.index') }}"
                                            class="nav-link {{ Route::is('classAssessments.index') ? 'active' : '' }}">Class
                                            Assessments</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('assessments.class-lists.index') }}"
                                            class="nav-link {{ Route::is('assessments.class-lists.index') ? 'active' : '' }}">Enter
                                            Student Results</a>
                                    </li>
                                @endif

                                @if (true)
                                    @if (true)
                                        {{-- <li
                                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['myClassStudentList', 'smyClassList', 'myClassList']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                                            <a href="#" class="nav-link"><span> Enter Student Results</span></a>
                                            <ul class="nav nav-group-sub" data-submenu-title="Manage Students">

                                                @if (true)
                                                    <li
                                                        class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['myClassStudentList', 'smyClassList', 'myClassList']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                        <a href="#"
                                                            class="nav-link {{ in_array(Route::currentRouteName(), ['smyClassList', 'myClassList']) ? 'active' : '' }}">Select
                                                            Academic
                                                            Period</a>
                                                        <ul class="nav nav-group-sub">
                                                            @foreach (\App\Repositories\Academics\AcademicPeriodRepository::getAllOpened('code') as $c)
                                                                <li class="nav-item"><a
                                                                        href="{{ route('class-list', Qs::hash($c->id)) }}"
                                                                        class="nav-link ">{{ $c->code }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                    </li>
                                                @endif

                                            </ul>
                                        </li> --}}

                                        <li
                                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['program-list']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                                            <a href="#" class="nav-link"><span> View Student Program
                                                    Results</span></a>
                                            <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                                                @if (true)
                                                    <li
                                                        class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['program-list']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                        <a href="#"
                                                            class="nav-link {{ in_array(Route::currentRouteName(), ['program-list']) ? 'active' : '' }}">Select
                                                            Academic
                                                            Period</a>
                                                        <ul class="nav nav-group-sub">
                                                            @foreach (\App\Repositories\Academics\AcademicPeriodRepository::getAllOpened('code') as $c)
                                                                <li class="nav-item"><a
                                                                        href="{{ route('program-list', Qs::hash($c->id)) }}"
                                                                        class="nav-link ">{{ $c->code }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                    </li>
                                                @endif

                                            </ul>
                                        </li>
                                    @endif

                                    {{--                                    --}}{{-- Grades list --}}
                                    @if (true)
                                        <li
                                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['getPublishProgramsCas', 'getPramResultsLevel', 'smyClassList', 'getPublishPrograms', 'getPramResults']) ? 'nav-item-expanded nav-item-open' : 'getPublishPrograms' }}">
                                            <a href="#" class="nav-link"><span>Board of Examiners</span></a>
                                            <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                                                <li
                                                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['reports.index']) ? 'nav-item-expanded nav-item-open' : 'getPublishPrograms' }}">
                                                    <a href="#" class="nav-link"><span> Reports</span></a>
                                                    <ul class="nav nav-group-sub"
                                                        data-submenu-title="Manage Students">
                                                        @if (true)
                                                            <li
                                                                class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['reports.index']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                                <a href="#"
                                                                    class="nav-link {{ in_array(Route::currentRouteName(), ['reports.index']) ? 'active' : '' }}">Academic
                                                                    Periods</a>
                                                                <ul class="nav nav-group-sub">
                                                                    {{--                                                                    @foreach (\App\Repositories\Academicperiods::getAllReadyPublish('code') as $c) --}}
                                                                    {{--                                                                        <li class="nav-item"><a --}}
                                                                    {{--                                                                                href="{{ route('reports.index', Qs::hash($c->id)) }}" --}}
                                                                    {{--                                                                                class="nav-link  {{ in_array(Route::currentRouteName(), ['reports.index' ]) ? 'active' : '' }}">{{ $c->code }}</a> --}}
                                                                    {{--                                                                        </li> --}}
                                                                    {{--                                                                    @endforeach --}}
                                                                </ul>

                                                            </li>
                                                        @endif

                                                    </ul>
                                                </li>
                                                <li
                                                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['smyClassList', 'getPublishPrograms', 'getPramResults', 'getPublishPrograms', 'getPramResultsLevel']) ? 'nav-item-expanded nav-item-open' : 'getPublishPrograms' }}">
                                                    <a href="#" class="nav-link"><span> Publish
                                                            results</span></a>
                                                    <ul class="nav nav-group-sub"
                                                        data-submenu-title="Manage Students">
                                                        @if (true)
                                                            <li
                                                                class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['getPramResultsLevel', 'getPublishPrograms', 'getPramResults']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                                <a href="#"
                                                                    class="nav-link {{ in_array(Route::currentRouteName(), ['getPublishPrograms', 'getPramResults']) ? 'active' : '' }}">Academic
                                                                    Periods</a>
                                                                <ul class="nav nav-group-sub">
                                                                    @foreach (\App\Repositories\Academics\ClassAssessmentsRepo::getAllReadyPublish('code') as $c)
                                                                        <li class="nav-item"><a
                                                                                href="{{ route('getPublishPrograms', Qs::hash($c->id)) }}"
                                                                                class="nav-link  {{ in_array(Route::currentRouteName(), ['getPublishPrograms']) ? 'active' : '' }}">{{ $c->code }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>

                                                            </li>
                                                        @endif

                                                    </ul>

                                                <li
                                                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['getPublishProgramsCas', 'getPublishPrograms', 'getPramResults']) ? 'nav-item-expanded nav-item-open' : 'getPublishPrograms' }}">
                                                    <a href="#" class="nav-link"><span> Publish CA
                                                            results</span></a>
                                                    <ul class="nav nav-group-sub"
                                                        data-submenu-title="Manage Students">
                                                        @if (true)
                                                            <li
                                                                class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['getPublishProgramsCas']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                                                <a href="#"
                                                                    class="nav-link {{ in_array(Route::currentRouteName(), ['getPublishProgramsCas']) ? 'active' : '' }}">Academic
                                                                    Periods</a>
                                                                <ul class="nav nav-group-sub">
                                                                    @foreach (\App\Repositories\Academics\ClassAssessmentsRepo::getAllReadyPublish('code') as $c)
                                                                        <li class="nav-item"><a
                                                                                href="{{ route('getPublishProgramsCas', Qs::hash($c->id)) }}"
                                                                                class="nav-link  {{ in_array(Route::currentRouteName(), ['getPublishProgramsCas']) ? 'active' : '' }}">{{ $c->code }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>

                                                            </li>
                                                        @endif

                                                    </ul>
                                                </li>

                                            </ul>
                                    @endif
                                @endif

                            @endif

                        </ul>
                    </li>
                @endif
                {{-- End Exam --}}

                @include('menus.' . Qs::getUserType() . '.menu')

                {{-- Manage Account --}}
                <li class="nav-item">
                    <a href="{{ route('my_account') }}"
                        class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i
                            class="icon-user"></i> <span>My Account</span></a>
                </li>

                {{-- Logout --}}
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <i class="icon-exit2"></i> <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
