@section('page_title', 'Manage Hostels')

@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Hostels</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-hostel" class="nav-link active" data-toggle="tab">Manage Hostels</a></li>
            <li class="nav-item"><a href="#new-hostel" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Hostel</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-hostel">
                <livewire:datatables.accommodation.hostels />
            </div>

            <div wire:ignore.self class="tab-pane fade" id="new-hostel">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('hostels.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="hostel_name" value="{{ old('hostel_name') }}" required type="text"
                                        class="form-control" placeholder="name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Location <span
                                        class="text-danger"></span></label>
                                <div class="col-lg-9">
                                    <input name="location" value="{{ old('location') }}" type="text"
                                        class="form-control" placeholder="location">
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click="refreshTable('HostelsTable')" id="ajax-btn" type="submit"
                                    class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
