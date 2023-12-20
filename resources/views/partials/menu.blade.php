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
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ "#" }}"><img src="" width="38" height="38"
                                                 class="rounded-circle" alt="photo"></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i>
                            &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ "#" }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item">
                    <a href="" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{--Academics--}}
                @if(Qs::userIsTeamSAT() || Qs::userIsSuperAdmin())


                    {{--Administrative--}}

                    @if(true)
                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['intakes.index','intakes.edit','prerequisites.index','prerequisites.edit','schools.index','schools.edit','classes.index','classes.edit', 'study-modes.index', 'period-types.index',
                                                        'period-types.edit','departments.index','departments.edit','programs.index','programs.edit','programs.show',
                                                        'courses.index','courses.edit','qualifications.index','qualifications.edit','levels.index','levels.edit']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Dept & prog Man</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
                                {{--Manage Departments--}}

                                <li class="nav-item">
                                    <a href="{{ route('schools.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['schools.index','schools.edit',]) ? 'active' : '' }}"><i
                                            class="icon-fence"></i> <span>School</span></a>
                                </li>

                                        <li class="nav-item">
                                            <a href="{{ route('departments.index') }}"
                                               class="nav-link {{ in_array(Route::currentRouteName(), ['departments.index','departments.edit',]) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Departments</span></a>
                                        </li>
                                        {{--Manage programs--}}
                                        <li class="nav-item">
                                            <a href="{{ route('programs.index') }}"
                                               class="nav-link {{ in_array(Route::currentRouteName(), ['programs.index','programs.edit','programs.show']) ? 'active' : '' }}"><i
                                                    class="icon-fence"></i> <span>Programs</span></a>
                                        </li>

                                        {{--Manage courses--}}
                                        <li class="nav-item">
                                            <a href="{{ route('courses.index') }}"
                                               class="nav-link {{ in_array(Route::currentRouteName(), ['courses.index','courses.edit',]) ? 'active' : '' }}"><i
                                                    class="icon-pin"></i> <span>Courses</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('qualifications.index') }}"
                                               class="nav-link {{ in_array(Route::currentRouteName(), ['qualifications.index','qualifications.edit',]) ? 'active' : '' }}"><i
                                                    class="icon-pin"></i> <span>Qualifications</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('levels.index') }}"
                                               class="nav-link {{ in_array(Route::currentRouteName(), ['levels.index','levels.edit',]) ? 'active' : '' }}"><i
                                                    class="icon-pin"></i> <span>Course Levels</span></a>
                                        </li>
                                        {{--Manage Study modes--}}
                                        <li class="nav-item">
                                            <a href="{{ route('study-modes.index') }}"
                                               class="nav-link {{ in_array(Route::currentRouteName(), ['study-modes.index','study-modes.edit']) ? 'active' : '' }}"><i
                                                    class="icon-home9"></i> <span>Study Modes</span></a>
                                        </li>
                                        {{-- Academic MANAGEMENT--}}
                                        <li class="nav-item">
                                            <a href="{{ route('period-types.index') }}"
                                               class="nav-link {{ in_array(Route::currentRouteName(), ['period-types.index','period-types.edit']) ? 'active' : '' }}"><i
                                                    class="icon-home9"></i> <span>Academic Period Types</span></a>
                                        </li>
                                        {{--Manage Prere--}}
                                <li class="nav-item">
                                    <a href="{{ route('prerequisites.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['prerequisites.index','prerequisites.edit']) ? 'active' : '' }}"><i
                                            class="icon-home9"></i> <span>Prerequisites</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('intakes.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['intakes.index','intakes.edit']) ? 'active' : '' }}"><i
                                            class="icon-home9"></i> <span>Intake</span></a>
                                </li>

                                    </ul>
                                </li>





                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['maritalStatues.create', 'maritalStatues.edit', 'maritalStatues.index', 'fees.create', 'fees.edit', 'fees.index', 'academicPeriods.create', 'academicPeriods.edit', 'academicPeriods.index', 'academicPeriodClasses.create', 'academicPeriodClasses.edit', 'academicPeriodClasses.index' ]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Academics</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Academic Period">

                                <li class="nav-item">
                                    <a href="{{ route('academic-periods.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['academicPeriods.create', 'academicPeriods.edit', 'academicPeriods.index']) ? 'active' : '' }}"><i
                                            class="icon-fence"></i> <span>Academic period</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('academic-period-classes.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['academicPeriodClasses.create', 'academicPeriodClasses.edit', 'academicPeriodClasses.index']) ? 'active' : '' }}"><i
                                            class="icon-fence"></i> <span>Academic period class</span></a>
                                </li>
                                    

                                    </ul>
                                </li>



                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['fees.create', 'fees.edit', 'fees.index' ]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Accounting</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Fees">

                                <li class="nav-item">
                                    <a href="{{ route('fees.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['fees.create', 'fees.edit', 'fees.index' ]) ? 'active' : '' }}"><i
                                            class="icon-fence"></i> <span>Fees</span></a>
                          </li>

                                    </ul>
                                </li>


                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.create', 'students.edit' ]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Student</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Students">

                                <li class="nav-item">
                                    <a href="{{ route('students.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['students.create', 'students.edit']) ? 'active' : '' }}"><i
                                            class="icon-fence"></i> <span>Admit student</span></a>
                                </li>

                                    

                                    </ul>
                                </li>



                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['maritalStatues.create', 'maritalStatues.edit', 'maritalStatues.index' ]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Other</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Academic Period">

                                <li class="nav-item">
                                    <a href="{{ route('academic-periods.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['maritalStatues.create', 'maritalStatues.edit', 'maritalStatues.index']) ? 'active' : '' }}"><i
                                            class="icon-fence"></i> <span>Marital status</span></a>
                                </li>

                                    

                                    </ul>
                                </li>








                                @endif


                @endif

                {{--End Exam--}}

                @include('menus.'.Qs::getUserType().'.menu')

                {{--Manage Account--}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i
                                class="icon-user"></i> <span>My Account</span></a>
                </li>

            </ul>
        </div>
    </div>
</div>
