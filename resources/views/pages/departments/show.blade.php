@extends('layouts.master')
@section('page_title', 'Department - '.$departments->name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-body">
                    <img style="width: 90%; height:90%" src="{{ $departments->cover }}" alt="photo" class="rounded-circle">
                    <br>
                    <h3 class="mt-3">{{ $departments->name }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item">
                            <a href="#basic-info" class="nav-link active" data-toggle="tab" >{{ $departments->name }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#all-programs" class="nav-link" data-toggle="tab" >Programs</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{--Basic Info--}}
                        <div class="tab-pane fade show active" id="basic-info">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="font-weight-bold">Name</td>
                                    <td>{{ $departments->name}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Description</td>
                                    <td>{{ $departments->description }}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="all-programs">
                            <table class="table datatable-button-html5-columns">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Qualification</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($departments->programs as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->code }}</td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->qualification->name }}</td>
                                        <td>{{ Str::limit($p->description,20) }}</td>
                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        @if(Qs::userIsTeamSA())
                                                            <a href="{{ route('programs.edit', $p->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                        @endif
                                                        @if(Qs::userIsTeamSA())
                                                            <a href="{{ route('programs.show', Qs::hash($p->id)) }}" class="dropdown-item"><i class="icon-eye"></i> Show</a>
                                                        @endif
                                                        @if(Qs::userIsSuperAdmin())
                                                            <a id="{{ $p->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                            <form method="post" id="item-delete-{{ $p->id }}" action="{{ route('programs.destroy', $p->id) }}" class="hidden">@csrf @method('delete')</form>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--User Profile Ends--}}

@endsection
