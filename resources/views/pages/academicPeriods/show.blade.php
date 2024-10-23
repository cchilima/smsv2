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
                        @can('academic_period.academic_report - download')
                            <div class="col">
                                <a href="{{ route('student.program.list', ['ac' => $academicPeriod->id]) }}"
                                    class="dropdown-item"><i class="icon-file-download"></i> Download Enrollment report PDF</a>
                                <a href="{{ route('student.program.list.csv', ['ac' => $academicPeriod->id]) }}"
                                    class="dropdown-item"><i class="icon-file-download"></i> Download Enrollment report
                                    Excel</a>
                            </div>
                        @endcan
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
                <div class="card-body show">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item"><a href="#ac-info" class="nav-link active" data-toggle="tab">Summary</a></li>
                        <li class="nav-item"><a href="#all-periods" class="nav-link" data-toggle="tab">Details</a>
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
                            <livewire:datatables.academics.academic-periods.fees :academicPeriodId="$academicPeriodId" />

                            @if (!empty($periods->id))
                                <div class="text-right">
                                    <a href="{{ route('academic-period-management.edit', Qs::hash($periods->id)) }}"
                                        id="ajax-btn" type="button" class="mbt-3 mt-4 btn btn-primary">Publish Fees
                                        <i class="icon-paperplane ml-2"></i></a>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            @can('academic_period.academic_information - show_section')
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">Academic Information</h6>
                        {!! Qs::getPanelOptions() !!}
                    </div>
                    <div class="card-body show">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="nav-item">
                                <a href="#all-ac-programs" class="nav-link active" data-toggle="tab">
                                    Running Programs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#all-classes" class="nav-link" data-toggle="tab">
                                    All Classes
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="all-ac-programs">
                                <div class="d-flex justify-content-between mb-2">
                                    @can('academic_period.running_programs - download')
                                        <div class="d-flex">
                                            <a href="{{ route('student.program.list', ['ac' => $academicPeriod->id]) }}"
                                                class="dropdown-item"><i class="icon-file-download"></i> Download PDF</a>
                                            <a href="{{ route('student.program.list.csv', ['ac' => $academicPeriod->id]) }}"
                                                class="dropdown-item"><i class="icon-file-download"></i> Download Excel</a>
                                        </div>
                                    @endcan
                                    <div>

                                    </div>
                                </div>

                                @livewire('datatables.academics.academicperiods.running-programs', [
                                    'academicPeriodId' => $academicPeriod->id,
                                ])
                            </div>

                            <div class="tab-pane fade" id="all-classes">
                                <div class="d-flex justify-content-between mb-2">
                                    @can('academic_period.running_classes - download')
                                        <div class="d-flex">
                                            <a href="{{ route('student.class.list', ['ac' => $academicPeriod->id]) }}"
                                                class="dropdown-item"><i class="icon-file-download"></i> Download PDF </a>
                                            <a href="{{ route('student.csv.class.list', ['ac' => $academicPeriod->id]) }}"
                                                class="dropdown-item"><i class="icon-file-download"></i> Download Excel </a>
                                        </div>
                                    @endcan
                                    <div>
                                        <a href="{{ route('student.class.list', ['ac' => $academicPeriod->id]) }}"
                                            class="dropdown-item"><i class="icon-add-to-list"></i> Add Class</a>
                                    </div>
                                </div>

                                @livewire('datatables.academics.academic-periods.classes', [
                                    'academicPeriodId' => $academicPeriod->id,
                                ])

                            </div>

                        </div>
                    </div>
                </div>
            @endcan

            @can('academic_period - batch_invoice')
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">Batch Invoicing</h6>
                        {!! Qs::getPanelOptions() !!}
                    </div>
                    <div class="card-body show">
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
            @endcan

        </div>
    </div>
@endsection
