@extends('layouts.master')
@section('page_title', 'Manage Towns')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Towns</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-towns" class="nav-link active" data-toggle="tab">Manage Towns</a>
                </li>
                <li class="nav-item"><a href="#new-town" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New Town</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-towns">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Province</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($towns as $town)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $town->name }}</td>
                                    <td>{{ $town->province->country->country ?? 'Other' }}</td>
                                    <td>{{ $town->province->name }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if (Qs::userIsTeamSA())
                                                        <a href="{{ route('towns.edit', $town->id) }}"
                                                            class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    @if (Qs::userIsSuperAdmin())
                                                        <a id="{{ $town->id }}" onclick="confirmDelete(this.id)"
                                                            href="#" class="dropdown-item"><i class="icon-trash"></i>
                                                            Delete</a>
                                                        <form method="post" id="item-delete-{{ $town->id }}"
                                                            action="{{ route('towns.destroy', $town->id) }}" class="hidden">
                                                            @csrf @method('delete')</form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="new-town">
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
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
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
@endsection
