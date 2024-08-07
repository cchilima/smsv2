@extends('layouts.master')
@section('page_title', 'Manage Class Assessment')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Class Assessment Exams Manager</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight" role="tablist" id="myTabs">
                <li class="nav-item">
                    <a href="#all-class-assessments" class="nav-link active" data-toggle="tab">Class Assessments</a>
                </li>
                <li class="nav-item">
                    <a href="#new-class-assessment" class="nav-link" data-toggle="tab">Assign Assessment</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#tab" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage class Assessment</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach ($academicPeriodsArray as $academicPeriod)
                            <a href="#ut-{{ Qs::hash($academicPeriod->id) }}" class="dropdown-item"
                                data-toggle="tab">{{ $academicPeriod->code }}s</a>
                        @endforeach
                    </div>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-class-assessments">
                    <livewire:datatables.academics.class-assessments />
                </div>

                <div class="tab-pane fade" id="new-class-assessment">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="ajax-store" method="post" action="{{ route('classAssessments.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold" for="nal_id">Academic
                                        Period: <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select onchange="getAcClassesPD(this.value)" data-placeholder="Choose..."
                                            name="academic" required id="nal_id" class="select-search form-control">
                                            <option value=""></option>
                                            @foreach ($open as $nal)
                                                <option value="{{ $nal->id }}">{{ $nal->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="classID" class="col-lg-3 col-form-label font-weight-semibold">Class:
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Choose..." required name="academic_period_class_id"
                                            id="classID" class=" select-search form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="assesmentID" class="col-lg-3 col-form-label font-weight-semibold">Assessment
                                        Type: <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Choose..." required name="assessment_type_id"
                                            id="assesmentID" class=" select-search form-control">
                                            <option value=""></option>
                                            @foreach ($assess as $a)
                                                <option {{ old('id') == $a->id ? 'selected' : '' }}
                                                    value="{{ $a->id }}">{{ $a->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Weighting (%)<span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="total" value="{{ old('total') }}" required type="number"
                                            min="1" class="form-control" placeholder="Total">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Due Date<span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input autocomplete="off" name="end_date" value="{{ old('end_date') }}"
                                            type="text" class="form-control date-pick" placeholder="ADue Date">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @foreach ($academicPeriodsArray as $academicPeriod)
                    <div class="tab-pane fade reloadThisDiv" id="ut-{{ Qs::hash($academicPeriod->id) }}">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Class Name</th>
                                    <th>Class code</th>
                                    <th>Assessment Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>course
                                @foreach ($academicPeriod->classes as $classAssessment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $classAssessment->course->name }}</td>
                                        <td>{{ $classAssessment->course->code }}</td>
                                        <td>
                                            <table class="table table-bordered table-hover table-striped">
                                                <tbody>
                                                    <td>Assessment Type</td>
                                                    <td>Total</td>
                                                    <td>End date</td>
                                                    @foreach ($classAssessment->class_assessments as $assessment)
                                                        <tr>
                                                            <td>{{ $assessment->assessment_type->name }}</td>
                                                            <td>
                                                                <span class="display-mode"
                                                                    id="display-mode{{ Qs::hash($assessment->id) }}">{{ $assessment->total }}</span>
                                                                <input type="text" class="edit-mode form-control"
                                                                    id="class{{ Qs::hash($assessment->id) }}"
                                                                    value="{{ $assessment->total }}"
                                                                    style="display: none;"
                                                                    onchange="updateExamResults('{{ Qs::hash($assessment->id) }}')">
                                                            </td>
                                                            <td>

                                                                <span class="display-mode"
                                                                    id="display-mode-enddate{{ Qs::hash($assessment->id) }}">{{ date('j F Y', strtotime($assessment->end_date)) }}</span>
                                                                <input autocomplete="off" type="text"
                                                                    class="edit-mode form-control date-pick"
                                                                    id="enddate{{ Qs::hash($assessment->id) }}"
                                                                    value="{{ $assessment->end_date }}"
                                                                    style="display: none;"
                                                                    onchange="updateExamResults('{{ Qs::hash($assessment->id) }}')">
                                                            </td>

                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>

                                        </td>

                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-left">

                                                        <a href="#" class="dropdown-item edit-total-link"><i
                                                                class="icon-pencil"></i> Edit</a>
                                                        @if (Qs::userIsSuperAdmin())
                                                            <a id="{{ Qs::hash($classAssessment['class_assessment_id']) }}"
                                                                onclick="confirmDelete(this.id)" href="#"
                                                                class="dropdown-item"><i class="icon-trash"></i>
                                                                Delete</a>
                                                            <form method="post"
                                                                id="item-delete-{{ Qs::hash($classAssessment['class_assessment_id']) }}"
                                                                action="{{ route('classAssessments.destroy', Qs::hash($classAssessment['class_assessment_id'])) }}"
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
                @endforeach

            </div>
        </div>
    </div>

    {{-- Class Assessment List Ends --}}

@endsection
