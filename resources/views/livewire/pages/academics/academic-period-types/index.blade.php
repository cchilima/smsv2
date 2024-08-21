@section('page_title', 'Manage Academic Period types')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Academic Period Types</h6>
        {!! 00 !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-types" class="nav-link active" data-toggle="tab">Manage Academic Period
                    types</a></li>
            <li class="nav-item"><a href="#new-type" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create
                    Academic Period type</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-types">
                <livewire:datatables.academics.academic-period-types />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-type">

                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('period-types.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Name of Academic Period type">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                                <div class="col-lg-9">
                                    <input name="description" value="{{ old('description') }}" type="text"
                                        class="form-control" placeholder="Description of Academic Period type">
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('AcademicPeriodTypesTable')"
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
