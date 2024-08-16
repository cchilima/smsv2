@section('page_title', 'Manage Departments')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Departments</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Departments</a>
            </li>
            <li class="nav-item"><a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Department</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-classes">
                <livewire:datatables.academics.departments />
            </div>

            <div wire:ignore.self class="tab-pane fade" id="new-class">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('departments.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="school_id" class="col-lg-3 col-form-label font-weight-semibold">School <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Class Type" class="form-control select"
                                        name="school_id" id="school_id">
                                        @foreach ($schools as $q)
                                            <option value="{{ $q->id }}">{{ $q->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Name of Department">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="description" value="{{ old('description') }}" required type="text"
                                        class="form-control" placeholder="description">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Upload Department
                                    Photo:</label>
                                <div class="col-lg-9">
                                    <input name="cover" accept="image/*" type="file" class="file-input"
                                        data-show-caption="false" data-show-upload="false" data-fouc>
                                    <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb
                                    </span>
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire.click="refreshTable('DepartmentsTable')" id="ajax-btn" type="submit"
                                    class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
