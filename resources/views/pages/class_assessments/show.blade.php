@extends('layouts.master')
@section('page_title', 'Upload results')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Class Assessment And Exam Manager</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item">
                    <a href="#Upload-results" class="nav-link active" data-toggle="tab">Upload Results</a>
                </li>
                <li class="nav-item">
                    <a href="#Add-results" class="nav-link" data-toggle="tab">Add Results</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="Upload-results">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Import CSV or Excel File
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if(empty($data))
                                        <!-- Import Form -->
                                        <form method="POST" action="{{ route('import.process') }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row">
                                                <input type="hidden" name="instructor" value=""
                                                       required>
                                                <label class="col-lg-3 col-form-label font-weight-semibold"
                                                       for="nal_id">Academic Period: <span class="text-danger">*</span></label>
                                                <div class="col-lg-9">
                                                    <select onchange="getRunningPrograms(this.value)"
                                                            data-placeholder="Choose..." name="academic" required
                                                            id="nal_id" class="select-search form-control">
                                                        <option value="">Choose</option>
                                                        @foreach($open as $ac)
                                                            <option {{ (old('nal_id') == $ac->id ? 'selected' : '') }} value="{{ Qs::hash($ac->id) }}">{{ $ac->code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="classID"
                                                       class="col-lg-3 col-form-label font-weight-semibold">Class: <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-lg-9">
                                                    <select data-placeholder="Choose..." required name="programID"
                                                            id="programID" class=" select-search form-control programID">
                                                        <option value="">Choose</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="classID"
                                                       class="col-lg-3 col-form-label font-weight-semibold">Choose File
                                                    <span class="text-danger">*</span></label>
                                                <div class="col-lg-9">
                                                    <input type="file" class="form-control-file" id="file" name="file"
                                                           required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Upload and Preview</button>
                                        </form>
                                    @else
                                        <!-- Data Preview Table -->
                                        <h2>Results Preview</h2>
                                        <table class="table table-bordered table-hover datatable-button-html5-columns">
                                            <thead>
                                            <tr>
{{--                                                @foreach($data[0] as $column => $value)--}}
{{--                                                    <th>{{ $column }}</th>--}}
{{--                                                @endforeach--}}
                                                <th> SIN </th>
                                                <th> CODE </th>
                                                <th> COURSE </th>
                                                <th> MARK </th>
                                                <th> ACADEMIC PERIOD </th>
                                                <th> PROGRAM </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data as $row)
                                                <tr>
                                                    @foreach($row as $value)
                                                        <td>{{ $value }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <!-- Import Button -->
                                        <div class="row col mb-4 mt-3">
                                            <form method="POST" action="{{ route('import.process') }}"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="file" class="form-control-file" id="file" name="file"
                                                           required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Import Data</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="Add-results">
                    <div class="row">
                        <div class="col-md-12">
                            @if(empty($data))
                            <form method="POST" action="{{ route('postedResults.process') }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold"
                                           for="nal_id">Academic Period: <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select onchange="getRunningPrograms(this.value)"
                                                data-placeholder="Choose..." name="academic" required class="select-search form-control">
                                            <option value="">Choose</option>
                                            @foreach($open as $ac)
                                                <option {{ (old('nal_id') == $ac->id ? 'selected' : '') }} value="{{ Qs::hash($ac->id) }}">{{ $ac->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="classID"
                                           class="col-lg-3 col-form-label font-weight-semibold">Program: <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Choose..." required name="programID"
                                                id="programID" class=" select-search form-control programID">
                                            <option value="">Choose</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="classID" class="col-lg-3 col-form-label font-weight-semibold">Student Number <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input required class="form-control" placeholder="Student Number" name="studentnumber" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="classID"
                                           class="col-lg-3 col-form-label font-weight-semibold">Marks <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input required class="form-control" placeholder="Marks" name="mark" type="text">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Upload Results</button>
                            </form>
                            @else
                                <p>Upload the results</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
