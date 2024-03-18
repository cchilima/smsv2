@extends('layouts.master')
@section('page_title', 'Edit Town')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Town</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post"
                        action="{{ route('towns.update', $town->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Town <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $town->name }}" required type="text"
                                    class="form-control" placeholder="Ndola">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Country: <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Country" required class="select-search form-control"
                                    name="country_id" id="country_id">
                                    <option disabled selected value=""></option>
                                    @foreach ($countries as $country)
                                        <option {{ $country->id === $town->province->country_id ? 'selected' : '' }}
                                            value="{{ $country->id }}">{{ $country->country }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Province: <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Province" required class="select-search form-control"
                                    name="province_id" id="province_id">
                                    <option disabled selected value=""></option>

                                    @foreach ($town->province->country->provinces ?? [] as $province)
                                        <option {{ $province->id === $town->province_id ? 'selected' : '' }}
                                            value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="text-right">
                            <button id="ajax-btn" type="submit" class="btn btn-primary">Update form <i
                                    class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Town Ends --}}

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
@endsection
