@section('page_title', 'Manage Bed Spaces')

@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Bed Spaces</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-statuses" class="nav-link active" data-toggle="tab">Manage Bed Spaces</a>
            </li>
            <li class="nav-item"><a href="#new-status" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Bed Space</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-statuses">
                <livewire:datatables.accommodation.bed-spaces />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-status">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('bed-spaces.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Room Name/Number <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="room_id" class="form-control select-search" required>
                                        <option value=""> Choose Room</option>
                                        @foreach ($rooms as $r)
                                            <option value="{{ $r->id }}">{{ $r->room_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Bed Number <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="bed_number" value="{{ old('bed_number') }}" required type="text"
                                        class="form-control" placeholder="Bed Number">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Is Available For
                                    Booking<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="is_available" class="form-control select-search" required>
                                        <option selected value="true"> True</option>
                                        <option value="false"> False</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('BedSpacesTable')" id="ajax-btn"
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
