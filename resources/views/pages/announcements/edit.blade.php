@extends('layouts.master')
@section('page_title', 'Edit - Announcement')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Announcement</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
        <div class="row">
                        <div class="col-md-10">
                            <form class="ajax-update" data-reload="#page-header" action="{{ route('announcements.update', $announcement->id) }}">
                            @csrf @method('PUT')

                            <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold" for="archived">Archived: </label>
                                        <div class="col-md-8 col-lg-9">
                                        <select class="select form-control" required id="archived" name="archived" data-fouc data-placeholder="Choose State">
                                            <option value="1" {{ $announcement->archived == true ? 'selected' : '' }}>archived</option>
                                            <option value="0" {{ $announcement->archived == false ? 'selected' : '' }}>unarchived</option>
                                        </select>
</div>
                                    </div>

                                <div class="form-group row">
                                    <label for="school_id" class="col-lg-3 col-form-label font-weight-semibold">Addressed to <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-lg-9">
                                        <select required data-placeholder="Select Class Type" class="form-control select" name="addressed_to" id="addressed_to">
                                            @foreach($userTypes as $ut)
                                                <option value="{{ $ut->id }}" {{ $announcement->addressed_to === $ut->id ? 'selected' : '' }}>{{ $ut->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Title <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="title" value="{{ $announcement->title }}" required type="text" class="form-control" placeholder="Title">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Description <span class="text-danger">*</span></label>
                                    <div class="col-md-12 col-lg-9">
                                        <textarea name="description" required type="text" class="form-control" placeholder="description"> {{ $announcement->description }} </textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-12 col-form-label font-weight-semibold">Upload Attachment: <br> {{ $announcement->attachment }}</label>
                                    <div class="col-md-8 col-lg-9">
                                    <input name="attachment" accept="image/*, .pdf" type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                                    <span class="form-text text-muted">Accepted Files: jpeg, png, pdf Max file size 2Mb  </span>
                                    </div>
                                </div>
                        

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
        </div>
    </div>

    {{--Class Edit Ends--}}

@endsection
