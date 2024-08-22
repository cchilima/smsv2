@section('page_title', 'Manage Academic Periods')

@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Academic Periods</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-open" class="nav-link active" data-toggle="tab">Open Academic Periods</a>
            </li>
            <li class="nav-item"><a href="#all-closed" class="nav-link" data-toggle="tab">Closed Academic Periods</a>
            </li>
            <li class="nav-item"><a href="#new-period" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i>
                    Create New Academic Period</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-open">
                <livewire:datatables.academics.academic-periods.open-academic-periods />
            </div>

            <div wire:ignore.self class="tab-pane fade show" id="all-closed">
                <livewire:datatables.academics.academic-periods.closed-academic-periods />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-period">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('academic-periods.store') }}">
                            @csrf
                            <!-- Add form fields for creating a new academic period -->
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Ac name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Code <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="code" value="{{ old('code') }}" required type="text"
                                        class="form-control" placeholder="Code">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Start Date <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="ac_start_date" value="{{ old('ac_start_date') }}" required
                                        type="text" class="form-control date-pick" placeholder="AC start Date">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">End Date <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="ac_end_date" value="{{ old('ace_end_date') }}" required type="text"
                                        class="form-control date-pick" placeholder="AC end date">
                                </div>
                            </div>

                            <!-- Use loops for dropdowns -->
                            <div class="form-group row">
                                <label for="period-type" class="col-lg-3 col-form-label font-weight-semibold">Period
                                    Type <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select wire:ignore.self required data-placeholder="Select type"
                                        class="form-control select-search" name="period_type_id" id="period-type">
                                        <option value=""></option>
                                        @foreach ($periodTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('OpenAcademicPeriodsTable')"
                                    id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i
                                        class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
