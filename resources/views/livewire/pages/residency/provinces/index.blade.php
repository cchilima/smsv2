@section('page_title', 'Manage Provinces')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Provinces</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-provinces" class="nav-link active" data-toggle="tab">Manage Provinces</a>
            </li>
            <li class="nav-item"><a href="#new-province" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Province</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-provinces">
                <livewire:datatables.residency.provinces />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-province">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('provinces.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Province <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Copperbelt">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Country: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Country" required
                                        class="select-search form-control" name="country_id" id="country_id">
                                        <option disabled selected value=""></option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click="refreshTable('ProvincesTable')" id="ajax-btn" type="submit"
                                    class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
