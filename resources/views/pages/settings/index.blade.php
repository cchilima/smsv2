@extends('layouts.master')
@section('page_title', 'Manage System Settings')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage System Settings</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-settings" class="nav-link active" data-toggle="tab">Manage System
                        Settings</a></li>
                {{-- <li class="nav-item"><a href="#new-setting" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create New System Setting</a></li> --}}
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-settings">
                    <livewire:datatables.settings.settings />

                    {{-- <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($settings as $setting)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $setting->type }}</td>
                                    <td>{{ $setting->description }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if (Qs::userIsTeamSA())
                                                        <a href="{{ route('settings.edit', $setting->id) }}"
                                                            class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    @if (Qs::userIsSuperAdmin())
                                                        <a id="{{ $setting->id }}" onclick="confirmDelete(this.id)"
                                                            href="#" class="dropdown-item"><i class="icon-trash"></i>
                                                            Delete</a>
                                                        <form method="post" id="item-delete-{{ $setting->id }}"
                                                            action="{{ route('settings.destroy', $setting->id) }}"
                                                            class="hidden">@csrf @method('delete')</form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                </div>

                {{-- <div class="tab-pane fade" id="new-setting">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('settings.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Type <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="type" value="{{ old('type') }}" required type="text"
                                            class="form-control" placeholder="Setting type">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="description" value="{{ old('description') }}" required type="text"
                                            class="form-control" placeholder="Description">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- System Setting List Ends --}}
@endsection
