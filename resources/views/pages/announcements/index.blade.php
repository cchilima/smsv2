@extends('layouts.master')
@section('page_title', 'Manage announcement')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Announcements</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage announcements</a>
                </li>
                <li class="nav-item"><a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New Announcement</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <livewire:datatables.notices.announcements />
                </div>

                <div class="tab-pane fade" id="new-class">
                    <div class="row">
                        <div class="col-md-10">
                            <form class="ajax-store" method="post" action="{{ route('announcements.store') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="school_id" class="col-lg-3 col-form-label font-weight-semibold">Addressed to
                                        <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select user type" class="form-control select"
                                            name="addressed_to" id="addressed_to">
                                            <option value="everyone" selected>Everyone</option>
                                            @foreach ($userTypes as $userType)
                                                <option value="{{ $userType->id }}">{{ $userType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Title <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="title" value="{{ old('title') }}" required type="text"
                                            class="form-control" placeholder="Title">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <textarea name="description" value="{{ old('description') }}" required type="text" class="form-control"
                                            placeholder="description"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Upload Attachment:</label>
                                    <div class="col-lg-9">
                                        <input name="attachment" accept="image/*, .pdf" type="file" class="file-input"
                                            data-show-caption="false" data-show-upload="false" data-fouc>
                                        <span class="form-text text-muted">Accepted Files: jpeg, png, pdf Max file size 2Mb
                                        </span>
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

    {{-- Class List Ends --}}

@endsection
