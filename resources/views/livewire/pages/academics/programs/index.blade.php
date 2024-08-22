@section('page_title', 'Manage Programs')

@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Programs</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Program</a></li>
            <li class="nav-item"><a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Programs</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-classes">
                <livewire:datatables.academics.programs />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-class">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('programs.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Name of Class">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Program Code <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="code" value="{{ old('code') }}" required type="text"
                                        class="form-control" placeholder="Program code">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class_type_id"
                                    class="col-lg-3 col-form-label font-weight-semibold">Department <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Class Type" class="form-control select"
                                        name="department_id" id="class_type_id">
                                        @foreach ($departments as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class_type_id"
                                    class="col-lg-3 col-form-label font-weight-semibold">Qualification <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Class Type" class="form-control select"
                                        name="qualification_id" id="class_type_id">
                                        @foreach ($qualifications as $q)
                                            <option value="{{ $q->id }}">{{ $q->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="description" value="{{ old('description') }}" required type="text"
                                        class="form-control" placeholder="Description">
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('ProgramsTable')" id="ajax-btn"
                                    type="submit" class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
