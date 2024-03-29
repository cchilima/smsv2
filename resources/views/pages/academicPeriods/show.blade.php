@extends('layouts.master')
@section('page_title', 'Academic Period - ' . $academicPeriod->code)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body">
                    {{-- <img style="width: 90%; height:90%" src="{{ '' }}" alt="photo" class="rounded-circle"> --}}
                    {{-- <br> --}}
                    <h3 class="mt-3">{{ $academicPeriod->code . ' - ' . $academicPeriod->name }}</h3>
                    <p>Registered Students : {{ $students }}</p>
                    <div class="row">
                        <div class="col">
                            <a href="{{ route('student.program.list', ['ac' => $academicPeriod->id]) }}"
                                class="dropdown-item"><i class="icon-file-download"></i> Download Enrollment report PDF</a>
                            <a href="{{ route('student.program.list.csv', ['ac' => $academicPeriod->id]) }}"
                                class="dropdown-item"><i class="icon-file-download"></i> Download Enrollment report
                                Excel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">General Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body collapse">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item"><a href="#ac-info" class="nav-link active" data-toggle="tab">Some
                                Information</a></li>
                        <li class="nav-item"><a href="#all-periods" class="nav-link" data-toggle="tab">Information</a>
                        </li>
                        <li class="nav-item"><a href="#all-fees" class="nav-link" data-toggle="tab">Fees</a></li>
                    </ul>
                    <div class="tab-content">
                        {{-- Basic Info --}}
                        <div class="tab-pane fade show active" id="ac-info">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Name</td>
                                        <td>{{ $academicPeriod->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Code</td>
                                        <td>{{ $academicPeriod->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Period Type</td>
                                        <td>{{ $academicPeriod->period_types->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Registered Students</td>
                                        <td>{{ $students }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Total classes</td>
                                        <td>{{ count($periodClasses) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Total Programs</td>
                                        <td>{{ count($programs) }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="all-periods">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                    @if (!empty($periods->study_mode->name))
                                        <tr>
                                            <td>Allowed Study Mode</td>
                                            <td>{{ $periods->study_mode->name }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($periods->intake->name))
                                        <tr>
                                            <td>Allowed Intake</td>
                                            <td>{{ $periods->intake->name }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($periods->view_results_threshold))
                                        <tr>
                                            <td>Results Threshold %</td>
                                            <td>{{ $periods->view_results_threshold }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($periods->exam_slip_threshold))
                                        <tr>
                                            <td>Download Exam Slip Threshold %</td>
                                            <td>{{ $periods->exam_slip_threshold }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($periods->registration_threshold))
                                        <tr>
                                            <td>Registration Threshold %</td>
                                            <td>{{ $periods->registration_threshold }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($periods->registration_date))
                                        <tr>
                                            <td>Registration Open Date</td>
                                            <td>{{ date('j F Y', strtotime($periods->registration_date)) }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($periods->late_registration_date))
                                        <tr>
                                            <td>Late Registration Date</td>
                                            <td>{{ date('j F Y', strtotime($periods->late_registration_date)) }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($periods->late_registration_end_date))
                                        <tr>
                                            <td>End of Late Registration Date</td>
                                            <td>{{ date('j F Y', strtotime($periods->late_registration_end_date)) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if (!empty($periods->id))
                                <div class="text-right">
                                    <a href="{{ route('academic-period-management.edit', Qs::hash($periods->id)) }}"
                                        id="ajax-btn" type="button" class="mbt-3 mt-4 btn btn-primary">Edit Information
                                        <i class="icon-paperplane ml-2"></i></a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="all-fees">
                            {{--                            <table class="table table-bordered table-hover table-striped datatable-button-html5-columns"> --}}
                            <table class="table datatable-button-html5-columns table-bordered table-hover ">
                                <thead>
                                    <tr>
                                        <th>Fee Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($feeInformation as $fee)
                                        <tr>
                                            <td>{{ $fee->fee->name }}</td>
                                            <td>{{ $fee->amount }}</td>
                                            <td>{{ $fee->status == 1 ? 'published' : 'Not Published' }}</td>
                                            <td>
                                                <a href="{{ route('academic-period-fees.edit', Qs::hash($fee->id)) }}"
                                                    class="dropdown-item"><i class="icon-pencil"></i></a>
                                                @if ($fee->status == 0)
                                                    <a href="{{ route('academic-period-fees.edit', Qs::hash($fee->id)) }}"
                                                        class="dropdown-item"><i class="icon-eye"></i></a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-right">
                                <a href="{{ route('academic-period-management.edit', Qs::hash($periods->id)) }}"
                                    id="ajax-btn" type="button" class="mbt-3 mt-4 btn btn-primary">Publish Fees
                                    <i class="icon-paperplane ml-2"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Academic Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body collapse">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#all-classes" class="nav-link active" data-toggle="tab">
                                All classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#all-ac-programs" class="nav-link" data-toggle="tab">
                                Running programs
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all-classes">

                            <table class="table datatable-button-html5-columns">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <a href="{{ route('student.class.list', ['ac' => $academicPeriod->id]) }}"
                                            class="dropdown-item"><i class="icon-file-download"></i> Download PDF </a>
                                        <a href="{{ route('student.csv.class.list', ['ac' => $academicPeriod->id]) }}"
                                            class="dropdown-item"><i class="icon-file-download"></i> Download Excel </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('student.class.list', ['ac' => $academicPeriod->id]) }}"
                                            class="dropdown-item"><i class="icon-add-to-list"></i> Add Class</a>
                                    </div>
                                </div>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course</th>
                                        <th>Students</th>
                                        <th>Instructor</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($periodClasses as $period)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $period->course->code }}</td>
                                            <td>{{ count($period->enrollments) }}</td>
                                            <td>{{ $period->instructor->first_name }}</td>

                                            <td class="text-center">
                                                <div class="list-icons">
                                                    <div class="dropdown">
                                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            @if (Qs::userIsTeamSA())
                                                                <a href="{{ route('academic-period-classes.edit', $period->id) }}"
                                                                    class="dropdown-item"><i class="icon-pencil"></i>
                                                                    Edit</a>
                                                            @endif

                                                            <a href="{{ route('student.one.class.list', ['classid' => $period->id, 'ac' => $academicPeriod->id]) }}"
                                                                class="dropdown-item"><i class="icon-paperplane"></i>
                                                                Download PDF List</a>
                                                            <a href="{{ route('student.csv.one.class.list', ['classid' => $period->id, 'ac' => $academicPeriod->id]) }}"
                                                                class="dropdown-item"><i class="icon-paperplane"></i>
                                                                Download CSV List</a>
                                                            @if (Qs::userIsSuperAdmin())
                                                                <a id="{{ $period->id }}"
                                                                    onclick="confirmDelete(this.id)" href="#"
                                                                    class="dropdown-item"><i class="icon-trash"></i>
                                                                    Delete</a>
                                                                <form method="post" id="item-delete-{{ $period->id }}"
                                                                    action="{{ route('academic-period-classes.destroy', $period->id) }}"
                                                                    class="hidden">@csrf @method('delete')</form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="all-ac-programs">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <a href="{{ route('student.program.list', ['ac' => $academicPeriod->id]) }}"
                                        class="dropdown-item"><i class="icon-file-download"></i> Download PDF</a>
                                    <a href="{{ route('student.program.list.csv', ['ac' => $academicPeriod->id]) }}"
                                        class="dropdown-item"><i class="icon-file-download"></i> Download Excel</a>
                                </div>
                                <div>

                                </div>
                            </div>
                            <table class="table datatable-button-html5-columns">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Program Code</th>
                                        <th>Program Name</th>
                                        <th>Qualification</th>
                                        <th>Department</th>
                                        <th>Students</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programs as $p)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $p->code }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->qualification->name }}</td>
                                            <td>{{ $p->department->name }}</td>
                                            <td>{{ $p->students_count }}</td>

                                            <td class="text-center">
                                                <div class="list-icons">
                                                    <div class="dropdown">
                                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            @if (Qs::userIsTeamSA())
                                                                <a href="{{ route('student.one.program.list', ['ac' => $academicPeriod->id, 'pid' => $p->id]) }}"
                                                                    class="dropdown-item"><i class="icon-paperplane"></i>
                                                                    Download PDF List</a>
                                                                <a href="{{ route('student.csv.one.program.list', ['ac' => $academicPeriod->id, 'pid' => $p->id]) }}"
                                                                    class="dropdown-item"><i class="icon-paperplane"></i>
                                                                    Download CSV List</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Batch Invoicing</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body collapse">
                    <div class="tab-content">

                        <form class="ajax-store" method="post"
                            action="{{ route('invoices.batchInvoicing', $academicPeriod->id) }}">
                            @csrf
                            <input name="academic_period" hidden value="{{ $academicPeriod->id }}" type="text">
                            <div class="text-left">
                                <button id="ajax-btn" type="submit" class="btn btn-primary">invoice students<i
                                        class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
