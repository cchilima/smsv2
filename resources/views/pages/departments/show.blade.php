@extends('layouts.master')
@section('page_title', 'Department - ' . $department->name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body">
                    <img style="width: 90%; height:90%" src="{{ $department->cover }}" alt="photo" class="rounded-circle">
                    <br>
                    <h3 class="mt-3">{{ $department->name }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#basic-info" class="nav-link active" data-toggle="tab">{{ $department->name }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#all-programs" class="nav-link" data-toggle="tab">Programs</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        <div class="tab-pane fade show active" id="basic-info">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Name</td>
                                        <td>{{ $department->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-justify">Description</td>
                                        <td>{{ $department->description }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="all-programs">
                            <livewire:datatables.academics.department-programs :departmentId="$departmentId" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Profile Ends --}}

@endsection
