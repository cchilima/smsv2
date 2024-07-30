@extends('layouts.master')
@section('page_title', 'Edit Program - '.$program->name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Program</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('programs.update', $program->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $program->name }}" required type="text" class="form-control" placeholder="Name of Class">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Program Code <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="code" value="{{ $program->code }}" required type="text" class="form-control" placeholder="Program code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Department <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select Class Type" class="form-control select" name="department_id" id="class_type_id">
                                    <option  value="{{ $program->department->id }}">{{ $program->department->name }}</option>
                                    @foreach($departments as $d)
                                        <option  value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Qualification <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select Class Type" class="form-control select" name="qualification_id" id="class_type_id">
                                    <option value="{{ $program->qualification->id }}">{{ $program->qualification->name }}</option>
                                    @foreach($qualifications as $q)
                                        <option value="{{ $q->id }}">{{ $q->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Description <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="description" value="{{ $program->description }}" required type="text" class="form-control" placeholder="Description">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class Edit Ends--}}

@endsection
