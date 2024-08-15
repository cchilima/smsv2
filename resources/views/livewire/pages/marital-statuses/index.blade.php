@section('page_title', 'Manage Marital Statuses')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Marital Statuses</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-statuses" class="nav-link active" data-toggle="tab">Manage Marital
                        Statuses</a></li>
                <li class="nav-item"><a href="#new-status" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New Marital Status</a></li>
            </ul>

            <div class="tab-content">
                <div wire:ignore.self class="tab-pane fade show active" id="all-statuses">
                    <livewire:datatables.settings.marital-statuses />
                </div>

                <div wire:ignore.self class="tab-pane fade" id="new-status">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store mb-4" method="post" action="{{ route('marital-statuses.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Status <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="status" value="{{ old('status') }}" required type="text"
                                            class="form-control" placeholder="Marital Status">
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
                                    <button 
                                    {{-- @click="$dispatch('pg:eventRefresh-MaritalStatusesTable')" --}} wire:click="refreshTable('MaritalStatusesTable')"
                                        id="ajax-btn" type="submit" class="btn btn-primary">Submit Form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>

                            {{-- <livewire:datatables.settings.marital-statuses /> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Marital Status List Ends --}}
    {{-- @endsection --}}
