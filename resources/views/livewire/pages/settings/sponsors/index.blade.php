@section('page_title', 'Sponsors')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Sponsors</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-sponsors" class="nav-link active" data-toggle="tab">Manage Sponsors</a></li>
            <li class="nav-item"><a href="#new-sponsor" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Sponsor</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-sponsors">
                <livewire:datatables.settings.sponsors />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-sponsor">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store mb-4" method="post" action="{{ route('sponsors.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('status') }}" required type="text"
                                        class="form-control"
                                        placeholder="GRZ | CDF | NGO">
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

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Phone <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="phone" value="{{ old('phone') }}" type="email"
                                           class="form-control" placeholder="phone">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Email <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="email" value="{{ old('email') }}"  type="text"
                                           class="form-control" placeholder="email">
                                </div>
                            </div>

                            <div class="text-right">
                                <button {{-- @click="$dispatch('pg:eventRefresh-MaritalStatusesTable')" --}}
                                    wire:click.debounce.1000ms="refreshTable('MaritalStatusesTable')" id="ajax-btn"
                                    type="submit" class="btn btn-primary">Submit Form <i
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
