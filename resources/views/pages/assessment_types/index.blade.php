@extends('layouts.master')
@section('page_title', 'Assessment Types')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Assessment Types</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-assessments" class="nav-link active" data-toggle="tab">Assessment Types</a>
                </li>
                <li class="nav-item"><a href="#new-assessments" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                        Create Assessment Type</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-assessments">
                    <livewire:datatables.academics.assessments.types />
                </div>

                <div class="tab-pane fade" id="new-assessments">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('assessments.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Assessment Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="{{ old('name') }}" required type="text"
                                            class="form-control" placeholder="Course Name">
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
