@section('page_title', 'Manage Academic Period Classes')

@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Academic Period Classes</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Academic Period
                    Classes</a></li>
            <li class="nav-item"><a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Academic Period Class</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-classes">
                <livewire:datatables.academics.academic-period-classes />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-class">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('academic-period-classes.store') }}">

                            @csrf

                            <!-- Use loops for dropdowns -->

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Academic Periods <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="academic_period_id" class="form-control select-search" required>
                                        @foreach ($academicPeriods as $period)
                                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Courses <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="course_id" class="form-control select-search" required>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }} -
                                                {{ $course->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Instructors <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="instructor_id" class="form-control select-search" required>
                                        @foreach ($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">{{ $instructor->first_name }}
                                                {{ $instructor->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('AcademicPeriodClassesTable')"
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
