@section('page_title', 'Manage Towns')
@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Towns</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-towns" class="nav-link active" data-toggle="tab">Manage Towns</a>
            </li>
            <li class="nav-item"><a href="#new-town" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Town</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-towns">
                <livewire:datatables.residency.towns />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-town">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('towns.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Town <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Ndola">
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

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Province: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Province" required
                                        class="select-search form-control" name="province_id" id="province_id">
                                        <option disabled selected value=""></option>
                                        {{-- Options fetched dynamically --}}
                                    </select>

                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('TownsTable')" id="ajax-btn"
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

{{-- Town List Ends --}}

{{-- Handle User Input --}}
<script>
    const getProvinces = (countryId, provinceSelector, townSelector) => {
        $.ajax({
            url: `/countries/${countryId}/provinces`,
            type: "GET",
            dataType: "json",
            success: (data) => {
                $(provinceSelector).empty();
                $(townSelector).empty();
                $(provinceSelector).append(`<option disabled selected></option>`)
                $.each(data, (_, value) => {
                    $(provinceSelector).append(
                        `<option value="${value.id}">${value.name}</option>`);
                });
            }
        });
    }


    $(document).ready(() => {

        $('#country_id').change(function() {
            const countryId = $(this).val();

            if (countryId) {
                getProvinces(countryId, '#province_id', '#town_id');
            } else {
                $('#province_id').empty();
            }
        });
    });
</script>
