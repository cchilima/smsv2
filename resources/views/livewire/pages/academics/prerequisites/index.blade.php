@section('page_title', 'Prerequisites')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Prerequisites</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-prerequisite" class="nav-link active" data-toggle="tab">Manage
                    Prerequisites</a></li>
            <li class="nav-item"><a href="#new-prerequisite-courses" class="nav-link" data-toggle="tab"><i
                        class="icon-plus2"></i> Create New Prerequisites</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-prerequisite">
                <livewire:datatables.academics.prerequisites />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-prerequisite-courses">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('prerequisites.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="courses" class="col-lg-3 col-form-label font-weight-semibold">Courses <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Course" class="form-control select-search"
                                        name="course_id" id="courses">
                                        <option value=""></option>
                                        @foreach ($courses as $c)
                                            <option value="{{ $c->id }}">{{ $c->code . ' - ' . $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="course-level"
                                    class="col-lg-3 col-form-label font-weight-semibold">Prerequisite Courses <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Prerequisite" multiple
                                        class="form-control select" name="prerequisite_course_id[]" id="course-level">
                                        @foreach ($courses as $c)
                                            <option value="{{ $c->id }}">{{ $c->code . ' - ' . $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('PrerequisitesTable')" id="ajax-btn"
                                    type="submit" class="btn btn-primary">Submit form <i
                                        class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
