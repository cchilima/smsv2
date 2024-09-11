@extends('layouts.master')
@section('page_title', 'Accommodation - ' . auth()->user()->first_name . ' ' . auth()->user()->last_name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Accommodation Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">

                        <li class="nav-item">
                            <a href="#status" class="nav-link active" data-toggle="tab">{{ 'My Applications' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#new" class="nav-link" data-toggle="tab">{{ 'Apply for Accommodation' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="status">
                            @livewire('datatables.accommodation.bookings', [
                                'student' => $student,
                            ])

                        </div>

                        <div class="tab-pane fade show" id="new">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="ajax-store" method="post"
                                        action="{{ route('student.apply_accommodation') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Hostel Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="hostel_id" id="hostel_ids"
                                                    onchange="getHostelRoomsOne(this.value)"
                                                    class="form-control select-search" required>
                                                    <option value=""> Choose Hostel</option>
                                                    @foreach ($hostel as $h)
                                                        <option value="{{ $h->id }}">{{ $h->hostel_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Room <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="room_ids" id="room_ids"
                                                    onchange="getRoomBedSpacesStudent(this.value)"
                                                    class="form-control select-search" required>
                                                    <option value=""> Choose Hostel</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Bed Space <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="bed_space_id" id="bed_space_ids"
                                                    class="form-control select-search" required>
                                                    <option value=""> Choose Bed Space</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label font-weight-semibold">Student Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="student_id" id="student_ids"
                                                    class="form-control select-search" required>
                                                    <option value=""> Choose Hostel</option>
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

                        <div class="tab-pane fade show" id="payment-history">

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
