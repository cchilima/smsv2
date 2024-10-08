@section('page_title', 'Manage Fees')

@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Fees</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-fees" class="nav-link active" data-toggle="tab">Manage Fees</a></li>
            <li class="nav-item"><a href="#new-fee" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create
                    New Fee</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-fees">
                <livewire:datatables.accounting.fees />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-fee">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('fees.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Fee name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Fee name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Type <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="type" class="form-control" required>
                                        <option value="Recurring">Recurring</option>
                                        <option value="Once off">Once off</option>
                                        <option value="Course repeat fee">Course repeat fee</option>
                                        <option value="Accommodation fee">Accommodation fee</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('FeesTable')" id="ajax-btn"
                                    type="submit" class="btn btn-primary">Submit form <i
                                        class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
