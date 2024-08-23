@section('page_title', 'Course Levels')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Courses Levels</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-course-levels" class="nav-link active" data-toggle="tab">Manage Courses
                    Levels</a>
            </li>
            <li class="nav-item"><a href="#new-course-level" class="nav-link" data-toggle="tab"><i
                        class="icon-plus2"></i>
                    Create New Course Levels</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-course-levels">
                <livewire:datatables.academics.course-levels />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-course-level">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('levels.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Level Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Course Name">
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('CourseLevelsTable')" id="ajax-btn"
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
