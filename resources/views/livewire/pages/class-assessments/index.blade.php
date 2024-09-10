@section('page_title', 'Manage Class Assessments')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Class Assessments</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight" role="tablist" id="myTabs">
            <li class="nav-item">
                <a href="#all-class-assessments" class="nav-link active" data-toggle="tab">Class Assessments</a>
            </li>
            <li class="nav-item">
                <a href="#new-class-assessment" class="nav-link" data-toggle="tab">Assign Assessment</a>
            </li>
        </ul>

        <div class="tab-content">
            <div wire:ignore class="tab-pane fade show active" id="all-class-assessments">
                <livewire:datatables.academics.assessments.class-assessments />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-class-assessment">
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
                                        @foreach ($openAcademicPeriods as $academicPeriod)
                                            <option value="{{ $academicPeriod->id }}">{{ $academicPeriod->code }}
                                            </option>
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
                                        @foreach ($assessmentTypes as $assessmentType)
                                            <option {{ old('id') == $assessmentType->id ? 'selected' : '' }}
                                                value="{{ $assessmentType->id }}">{{ $assessmentType->name }}</option>
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
                                <button wire:click.debounce.1000ms="refreshTable('ClassAssessmentsTable')"
                                    id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                        class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
