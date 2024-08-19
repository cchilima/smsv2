@section('page_title', 'Manage Countries')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Countries</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-countries" class="nav-link active" data-toggle="tab">Manage Countries</a>
            </li>
            <li class="nav-item"><a href="#new-country" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Country</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-countries">
                <livewire:datatables.residency.countries />
            </div>

            <div wire:ignore.self class="tab-pane fade" id="new-country">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('countries.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Country <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Country">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Nationality <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="nationality" value="{{ old('nationality') }}" required type="text"
                                        class="form-control" placeholder="Nationality">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Alpha Code 2 <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="alpha_2_code" value="{{ old('alpha_2_code') }}" required type="text"
                                        class="form-control" placeholder="ZM">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Alpha Code 3 <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="alpha_3_code" value="{{ old('alpha_3_code') }}" required type="text"
                                        class="form-control" placeholder="ZMB">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Dialing Code <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="dialing_code" value="{{ old('dialing_code') }}" required type="text"
                                        class="form-control" placeholder="+260">
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click="refreshTable('CountriesTable')" id="ajax-btn" type="submit"
                                    class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
