@section('page_title', 'Manage Schools')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Schools</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-modes" class="nav-link active" data-toggle="tab">Manage Schools</a></li>
            <li class="nav-item"><a href="#new-mode" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create
                    New School</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-modes">
                <livewire:datatables.academics.schools />
            </div>

            <div wire:ignore.self class="tab-pane fade" id="new-mode">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('schools.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Name of School">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                                <div class="col-lg-9">
                                    <input name="description" value="{{ old('description') }}" type="text"
                                        class="form-control" placeholder="Description of Study Mode">
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click="refreshTable('SchoolsTable')" id="ajax-btn" type="submit"
                                    class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>