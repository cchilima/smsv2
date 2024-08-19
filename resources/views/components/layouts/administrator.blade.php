@php
    use App\Helpers\Qs;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('/css/material.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/app.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@200;300&display=swap" rel="stylesheet">

    <title>{{ $title ?? '' }}</title>

    <style>
        #sidenav-1 {
            top: 58px;
        }

        .sidenav-overlay {
            opacity: 0 !important;
        }
    </style>

    @livewireStyles

</head>

<body>

    <!-- NAVBAR -->
    <header>
        <nav>
            <div class="nav-wrapper primary">
                <div class="row">
                    <div class="col s12">
                        <a data-target="sidenav-1" class="left sidenav-trigger show-on-medium-and-up"><i
                                class="material-icons white-text">menu</i></a>
                        <a href="{{ route('login') }}" class="brand-logo right mx-2"><img width="60" height="60"
                                src="{{ asset('images/logo.png') }}" alt=""></a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    @auth

        <!-- RIGHT SIDEBAR -->
        <ul id="sidenav-1" class="sidenav primary white-text">

            <li class="white">
                <ul class="collapsible collapsible-accordion primary ">

                    {{-- Academics --}}
                    @if (Qs::userIsSuperAdmin() || Qs::userIsAdmin())
                        {{-- Administrative --}}

                        @if (true)
                            <li
                                class="white {{ in_array(Route::currentRouteName(), [
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
                                <a href="#" class="collapsible-header waves-effect waves-blue primary white-text"><i
                                        class="material-icons white-text"></i> <span> Departments & Programs</span></a>

                                <div class="collapsible-body">
                                    <ul data-submenu-title="Manage Academics">
                                        {{-- Manage Departments --}}

                                        <li class="nav-item">
                                            <a href="{{ route('schools.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['schools.index', 'schools.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>School</span></a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('departments.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['departments.index', 'departments.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Departments</span></a>
                                        </li>
                                        {{-- Manage programs --}}
                                        <li class="nav-item">
                                            <a href="{{ route('programs.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['programs.index', 'programs.edit', 'programs.show']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Programs</span></a>
                                        </li>

                                        {{-- Manage courses --}}
                                        <li class="nav-item">
                                            <a href="{{ route('courses.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['courses.index', 'courses.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Courses</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('qualifications.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['qualifications.index', 'qualifications.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Qualifications</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('levels.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['levels.index', 'levels.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Course Levels</span></a>
                                        </li>
                                        {{-- Manage Study modes --}}
                                        <li class="nav-item">
                                            <a href="{{ route('study-modes.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['study-modes.index', 'study-modes.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Study Modes</span></a>
                                        </li>
                                        {{-- Academic MANAGEMENT --}}
                                        <li class="nav-item">
                                            <a href="{{ route('period-types.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['period-types.index', 'period-types.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Academic Period Types</span></a>
                                        </li>
                                        {{-- Manage Prere --}}
                                        <li class="nav-item">
                                            <a href="{{ route('prerequisites.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['prerequisites.index', 'prerequisites.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Prerequisites</span></a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('intakes.index') }}"
                                                class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['intakes.index', 'intakes.edit']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Intake</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        <li
                            class="white {{ in_array(Route::currentRouteName(), ['academic-period-management.index', 'academic-period-fees.edit', 'academic-period-management.edit', 'academic-periods.create', 'academic-periods.edit', 'academic-periods.index']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a class="collapsible-header waves-effect waves-blue primary white-text" href="#"
                                class="nav-link"><i class="material-icons white-text"></i> <span>
                                    Academics</span></a>

                            <div class="collapsible-body">
                                <ul class="nav nav-group-sub" data-submenu-title="Manage Academic Period">
                                    <li class="nav-item">
                                        <a href="{{ route('academic-periods.index') }}"
                                            class="waves-effect waves-blue primary white-text{{ in_array(Route::currentRouteName(), ['academic-period-management.index', 'academic-period-management.edit', 'academic-period-fees.edit', 'academic-periods.create', 'academic-periods.edit', 'academic-periods.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Academic period</span></a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('academic-period-classes.index') }}"
                                            class="waves-effect waves-blue primary white-text{{ in_array(Route::currentRouteName(), ['academic-period-classes.create', 'academic-period-classes.edit', 'academic-period-classes.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Academic period class</span></a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('enrollments.index') }}"
                                            class="waves-effect waves-blue primary white-text{{ in_array(Route::currentRouteName(), ['enrollments.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Enrollments</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li
                            class="white {{ in_array(Route::currentRouteName(), ['credit.notes', 'student.list', 'creditors', 'aged.receivables', 'revenue.analysis', 'invoices', 'fees.create', 'fees.edit', 'fees.index']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a class="collapsible-header waves-effect waves-blue primary white-text" href="#"><i
                                    class="icon-cash3"></i> <span>Accounting</span></a>
                            <div class="collapsible-body">
                                <ul class="collapsible collapsible-accordion">
                                    <li class="nav-item">
                                        <a href="{{ route('fees.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['fees.create', 'fees.edit', 'fees.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Fees</span></a>
                                    </li>

                                    <li class="nav-item">
                                    <a href="{{ route('accounting.approve_credit_notes') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['accounting.approve_credit_notes']) ? 'active' : '' }}">
                                        <span>Approve Credit Notes</span></a>
                                     </li>

                                    <li
                                        class="{{ in_array(Route::currentRouteName(), ['credit.notes', 'student.list', 'creditors', 'aged.receivables', 'revenue.analysis', 'invoices']) ? 'active' : '' }}">
                                        <a class="collapsible-header waves-effect waves-blue primary white-text"
                                            href="#"><i class="icon-book"></i> <span>Reports</span></a>
                                        <div class="collapsible-body">
                                            <ul class="collapsible collapsible-accordion">
                                                <li
                                                    class="{{ in_array(Route::currentRouteName(), ['revenue.analysis', 'invoices']) ? 'active' : '' }}">
                                                    <a class="collapsible-header waves-effect waves-blue primary white-text"
                                                        href="#">Revenue</a>
                                                    <div class="collapsible-body">
                                                        <ul>
                                                            <li class="nav-item"><a
                                                                    href="{{ route('revenue.analysis') }}"
                                                                    class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['revenue.analysis']) ? 'active' : '' }}">Revenue
                                                                    Analysis</a></li>
                                                            <li class="nav-item"><a href="{{ route('invoices') }}"
                                                                    class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['invoices']) ? 'active' : '' }}">Invoices</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li
                                                    class="{{ in_array(Route::currentRouteName(), ['transactions', 'credit.notes', 'aged.receivables']) ? 'active' : '' }}">
                                                    <a class="collapsible-header waves-effect waves-blue primary white-text"
                                                        href="#">Receivables</a>
                                                    <div class="collapsible-body">
                                                        <ul>
                                                            <li class="nav-item"><a href="{{ route('transactions') }}"
                                                                    class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['transactions']) ? 'active' : '' }}">Transactions</a>
                                                            </li>
                                                            <li class="nav-item"><a
                                                                    href="{{ route('failed.transaction') }}"
                                                                    class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['failed.transaction']) ? 'active' : '' }}">Failed
                                                                    Online Transactions</a></li>
                                                            <li class="nav-item"><a
                                                                    href="{{ route('aged.receivables') }}"
                                                                    class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['aged.receivables']) ? 'active' : '' }}">Aged
                                                                    Receivables</a></li>
                                                            <li class="nav-item"><a href="#"
                                                                    class="waves-effect waves-blue primary white-text">Bank
                                                                    Reconciliation</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li
                                                    class="{{ in_array(Route::currentRouteName(), ['credit.notes', 'student.list']) ? 'active' : '' }}">
                                                    <a class="collapsible-header waves-effect waves-blue primary white-text"
                                                        href="#">General</a>
                                                    <div class="collapsible-body">
                                                        <ul>
                                                            <li class="nav-item"><a href="{{ route('student.list') }}"
                                                                    class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['student.list']) ? 'active' : '' }}">Student
                                                                    List</a></li>
                                                            <li class="nav-item"><a href="#"
                                                                    class="waves-effect waves-blue primary white-text">Chart
                                                                    of Accounts</a></li>
                                                            <li class="nav-item"><a href="{{ route('credit.notes') }}"
                                                                    class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['credit.notes']) ? 'active' : '' }}">Credit
                                                                    Notes</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('payment-methods.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['payment-methods.create', 'payment-methods.edit', 'payment-methods.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Payment Methods</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('application.pending_collection') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['application.pending_collection']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Collect Application Payment</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Students -->
                        <li
                            class="white {{ in_array(Route::currentRouteName(), ['search', 'students.create', 'students.edit']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a class="collapsible-header waves-effect waves-blue primary white-text" href="#"><i
                                    class="icon-users"></i> <span>Students</span></a>
                            <div class="collapsible-body">
                                <ul class="collapsible collapsible-accordion">
                                    <li class="nav-item">
                                        <a href="{{ route('students.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['students.create', 'students.edit']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Admit student</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('search') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['search', 'students.edit']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Student Information</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Admissions -->
                        <li
                            class="white {{ in_array(Route::currentRouteName(), ['application.index', 'application.show', 'start-application', 'application.complete_application', 'application.save_application', 'application.summary_reports']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a class="collapsible-header waves-effect waves-blue primary white-text" href="#"><i
                                    class="icon-user-plus"></i> <span>Admissions</span></a>
                            <div class="collapsible-body">
                                <ul class="collapsible collapsible-accordion">
                                    <li class="nav-item">
                                        <a href="{{ route('start-application') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['applications.initiate']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>New Student Application</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('application.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['applications.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Applications</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('application.summary_reports') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['application.summary_reports']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Reports</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Accommodation -->
                        <li
                            class="white {{ in_array(Route::currentRouteName(), ['hostels.create', 'hostels.edit', 'hostels.index', 'rooms.index', 'rooms.edit', 'bookings.index', 'bookings.edit', 'bed-spaces.index', 'bed-spaces.edit']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a class="collapsible-header waves-effect waves-blue primary white-text" href="#"><i
                                    class="icon-office"></i> <span>Accommodation</span></a>
                            <div class="collapsible-body">
                                <ul class="collapsible collapsible-accordion">
                                    <li class="nav-item">
                                        <a href="{{ route('hostels.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['hostels.create', 'hostels.edit', 'hostels.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Hostels</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('rooms.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['rooms.create', 'rooms.edit', 'rooms.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Rooms</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('start-application') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['start-application']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Admit Student</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bed-spaces.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['bed-spaces.create', 'bed-spaces.edit', 'bed-spaces.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Bed Spaces</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bookings.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['bookings.create', 'bookings.edit', 'bookings.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Bed Space Booking</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Reports -->
                        <li
                            class="white {{ in_array(Route::currentRouteName(), ['audit.trail.reports', 'student.list.reports', 'registers.reports', 'enrollments.reports']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a class="collapsible-header waves-effect waves-blue primary white-text" href="#"><i
                                    class="icon-file-stats"></i> <span>Reports</span></a>
                            <div class="collapsible-body">
                                <ul class="collapsible collapsible-accordion">
                                    <li class="nav-item">
                                        <a href="{{ route('enrollments.reports') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['enrollments.reports']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Enrollments</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('registers.reports') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['registers.reports']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Exam Registers</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('student.list.reports') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['student.list.reports']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Student List</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('audit.trail.reports') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['audit.trail.reports']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Audit Trail</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Other -->
                        <li
                            class="white {{ in_array(Route::currentRouteName(), ['announcements.index', 'marital-statuses.create', 'marital-statuses.edit', 'marital-statuses.index', 'countries.index', 'countries.edit', 'provinces.index', 'provinces.edit', 'towns.index', 'towns.edit']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a class="collapsible-header waves-effect waves-blue primary white-text" href="#"><i
                                    class="icon-equalizer"></i> <span>Other</span></a>
                            <div class="collapsible-body">
                                <ul class="collapsible collapsible-accordion">
                                    <li class="nav-item">
                                        <a href="{{ route('announcements.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['announcements.create', 'announcements.edit', 'announcements.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Announcements</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('countries.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['countries.create', 'countries.edit', 'countries.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Countries</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('provinces.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['provinces.create', 'provinces.edit', 'provinces.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Provinces</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('towns.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['towns.create', 'towns.edit', 'towns.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Towns</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('marital-statuses.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['maritalStatues.create', 'maritalStatues.edit', 'maritalStatues.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Marital statuses</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('audits.index') }}"
                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['maritalStatues.create', 'maritalStatues.edit', 'maritalStatues.index']) ? 'active' : '' }}"><i
                                                class="icon-fence"></i> <span>Audit Reports</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if (Qs::userIsSuperAdmin() || Qs::userIsAdmin())
                        <li
                            class="white {{ in_array(Route::currentRouteName(), [
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
                                'import.process',
                                'getPublishPrograms',
                                'getPramResults',
                                'myClassStudentList',
                                'myClassList',
                                'reports.index',
                                'getPramResultsLevel',
                            ])
                                ? 'nav-item-expanded nav-item-open'
                                : '' }}">
                            <a href="#" class="collapsible-header waves-effect waves-blue primary white-text"><i
                                    class="icon-books"></i> <span>Exams</span></a>
                            <div class="collapsible-body">
                                <ul class="collapsible collapsible-accordion">
                                    @if (true)
                                        @if (!Qs::userIsInstructor())
                                            <li class="nav-item">
                                                <a href="{{ route('assessments.index') }}"
                                                    class="waves-effect waves-blue primary white-text {{ Route::is('assessments.index') ? 'active' : '' }}"><i
                                                        class="icon-fence"></i> <span>Create CA And Exam</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('classAssessments.index') }}"
                                                    class="waves-effect waves-blue primary white-text {{ Route::is('classAssessments.index') ? 'active' : '' }}"><i
                                                        class="icon-fence"></i> <span>Assign CA To Class</span></a>
                                            </li>
                                        @endif

                                        <li class="nav-item">
                                            <a class="collapsible-header waves-effect waves-blue primary white-text"><span>Enter
                                                    Student Results</span></a>
                                            <div class="collapsible-body">
                                                <ul class="collapsible collapsible-accordion">
                                                    <li class="nav-item">
                                                        <a
                                                            class="collapsible-header waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['myClassStudentList', 'smyClassList', 'myClassList']) ? 'active' : '' }}"><span>Select
                                                                Academic Period</span></a>
                                                        <div class="collapsible-body">
                                                            <ul>
                                                                @foreach (\App\Repositories\Academics\AcademicPeriodRepository::getAllOpened('code') as $c)
                                                                    <li class="nav-item"><a
                                                                            href="{{ route('class-list', Qs::hash($c->id)) }}"
                                                                            class="waves-effect waves-blue primary white-text">{{ $c->code }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li class="nav-item">
                                            <a class="collapsible-header waves-effect waves-blue primary white-text"><span>Board
                                                    of Examiners</span></a>
                                            <div class="collapsible-body">
                                                <ul class="collapsible collapsible-accordion">
                                                    <li class="nav-item">
                                                        <a
                                                            class="collapsible-header waves-effect waves-blue primary white-text"><span>Reports</span></a>
                                                        <div class="collapsible-body">
                                                            <ul>
                                                                <li class="nav-item"><a href="#"
                                                                        class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['reports.index']) ? 'active' : '' }}">Academic
                                                                        Periods</a></li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a
                                                            class="collapsible-header waves-effect waves-blue primary white-text"><span>Publish
                                                                Results</span></a>
                                                        <div class="collapsible-body">
                                                            <ul>
                                                                @foreach (\App\Repositories\Academics\ClassAssessmentsRepo::getAllReadyPublish('code') as $c)
                                                                    <li class="nav-item"><a
                                                                            href="{{ route('getPublishPrograms', Qs::hash($c->id)) }}"
                                                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['getPublishPrograms']) ? 'active' : '' }}">{{ $c->code }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a
                                                            class="collapsible-header waves-effect waves-blue primary white-text"><span>Publish
                                                                CA Results</span></a>
                                                        <div class="collapsible-body">
                                                            <ul>
                                                                @foreach (\App\Repositories\Academics\ClassAssessmentsRepo::getAllReadyPublish('code') as $c)
                                                                    <li class="nav-item"><a
                                                                            href="{{ route('getPublishProgramsCas', Qs::hash($c->id)) }}"
                                                                            class="waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['getPublishProgramsCas']) ? 'active' : '' }}">{{ $c->code }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif

                    {{-- Manage Account --}}
                    <li class="nav-item">
                        <a href="{{ route('my_account') }}"
                            class="collapsible-header waves-effect waves-blue primary white-text {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i
                                class="icon-user"></i> <span>My Account</span></a>
                    </li>

                    {{-- Logout --}}
                    <li class="nav-item">
                        <a class="collapsible-header waves-effect waves-blue primary white-text"
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="nav-link">
                            <i class="icon-exit2"></i> <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>

                </ul>
            </li>

        </ul>
    @endauth

    <main>
        {{ $slot }}
    </main>

    @livewireScripts

</body>

</html>
