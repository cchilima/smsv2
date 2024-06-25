@extends('layouts.master')
@section('page_title', 'Audit reports')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Audit Reports</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-statuses" class="nav-link active" data-toggle="tab">Manage Marital Statuses</a></li>
                <li class="nav-item"><a href="#new-status" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Marital Status</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-statuses">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>New values</th>
                            <th>old Values</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($audits as $audit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $audit->user->first_name.' '.$audit->user->last_name }}</td>
                                <td>{{ $audit->event }}</td>
                                <td>{{ json_encode($audit->old_values) }}</td>
                                <td>{{ json_encode($audit->new_values) }}</td>
                                <td>{{ date('j F Y H-i-s', strtotime($audit->created_at)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="new-status">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('marital-statuses.store')  }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Status <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="status" value="{{ old('status') }}" required type="text" class="form-control" placeholder="Marital Status">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Description <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="description" value="{{ old('description') }}" required type="text" class="form-control" placeholder="Description">
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
        </div>
    </div>

    {{-- Marital Status List Ends --}}
@endsection
