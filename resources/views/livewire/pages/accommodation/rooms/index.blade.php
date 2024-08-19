@section('page_title', 'Manage Rooms')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Rooms</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-rooms" class="nav-link active" data-toggle="tab">Manage Rooms</a></li>
            <li class="nav-item"><a href="#new-rooms" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Rooms</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-rooms">
                <livewire:datatables.accommodation.rooms />
            </div>

            <div wire:ignore.self class="tab-pane fade" id="new-rooms">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('rooms.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Hostel Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="hostel_id" class="form-control select-search" required>
                                        <option value=""> Choose Hostel</option>
                                        @foreach ($hostels as $h)
                                            <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Room Number <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="room_number" value="{{ old('room_number') }}" required type="text"
                                        class="form-control" placeholder="room_number">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Capacity <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="capacity" value="{{ old('description') }}" required type="text"
                                        class="form-control" placeholder="Capacity">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Gender <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="gender" class="form-control select-search" required>
                                        <option value=""> Choose Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click="refreshTable('RoomsTable')" id="ajax-btn" type="submit"
                                    class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
